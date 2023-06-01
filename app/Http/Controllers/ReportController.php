<?php

namespace App\Http\Controllers;

use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ReportController
{
    public function month()
    {
        $current_user = auth()->user();
        $currentRoleName = $current_user->getRoleNames()[0];
        $user_id = auth()->id();
        $data = [];
        $dataTotal = [];
        $bonusTotal = [];
        $badTotal = [];
        $dataBad = [];

        if ($currentRoleName == 'editor' || $currentRoleName == 'QA') {
            $users = Admin::with(['QAMonthTasks', 'EditorMonthTasks'])->where('id', $user_id)->whereHas('roles', function (Builder $subQuery) {
                $subQuery->whereIn(config('permission.table_names.roles') . '.name', ['QA', 'editor']);
            })->get();
        } else {
            $users = Admin::with(['QAMonthTasks', 'EditorMonthTasks'])->whereHas('roles', function (Builder $subQuery) {
                $subQuery->whereIn(config('permission.table_names.roles') . '.name', ['QA', 'editor']);
            })->get();
        }

        foreach ($users as $user) {
            //get dates
            $dates = collect();
            for ($i = 0; $i < Carbon::now()->daysInMonth; $i++) {
                $date = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
                $dates->put($date, 0);
            }

            // get condition each user
            $bad = $dates->toArray();
            $roleName = $user->getRoleNames()[0];
            $conditionAssigner = "";
            if ($roleName == 'editor') {
                $conditionAssigner = "editor_id";

                // get bad
                $tasks = $user->EditorMonthTasks;
                foreach ($tasks as $task) {
                    if ($task->redo_note) {
                        $date = Carbon::createFromFormat('Y-m-d H:i:s', $task->created_at)->format('Y-m-d');
                        $badMoney = Admin::BAD_FEE[$task->redo_note];
                        $bad[$date] = $bad[$date] + $badMoney;
                    }
                }
            } else if ($roleName == 'QA') {
                $conditionAssigner = 'QA_id';
            }

            // get counts
            $counts = Tasks::where($conditionAssigner, $user->id)->where([['created_at', '>=', $dates->keys()->first()]])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE( created_at ) as date'),
                    DB::raw('sum(estimate) AS "count"'),
                    DB::raw('sum(estimate_QA) AS "count_QA"'),
                ]);

            // mapping data
            $dataDate = [];
            $dataBonus = [];
            foreach ($dates as $key => $date) {
                if ($roleName == 'editor') {
                    $totalTime = $counts->where('date', $key)->first()->count ?? 0;
                } else if ($roleName == 'QA') {
                    $totalTime = $counts->where('date', $key)->first()->count_QA ?? 0;
                }
                $dataDate[] = $totalTime;
                $dataBonus[] = ($totalTime >= 600) ? 100000 :0;
            }

            // full data
            if (empty($dataTotal)) {
                $dataTotal = $dataDate;
            } else {
                $dataTotal = array_map(function () {
                    return array_sum(func_get_args());
                }, $dataTotal, $dataDate);
            }

            //bonus
            if (empty($dataTotal)) {
                $bonusTotal = $dataBonus;
            } else {
                $bonusTotal = array_map(function () {
                    return array_sum(func_get_args());
                }, $bonusTotal, $dataBonus);
            }

            //bad
            if ($roleName == 'editor') {
                if (empty($dataTotal)) {
                    $badTotal = $bad;
                } else {
                    $badTotal = array_map(function () {
                        return array_sum(func_get_args());
                    }, $badTotal, $bad);
                }
                $bad = array_map(function () {
                    return array_sum(func_get_args());
                }, $bad, []);
                $dataBad[$user->fullName] = $bad;
            }

            $data[$user->fullName] = $dataDate;
        }

        $sumTotal = array_sum($dataTotal);
        $sumBonus = array_sum($bonusTotal);
        $sumBad = array_sum($badTotal);

        return view('admin.reports.month', compact('data', 'dataTotal', 'sumTotal', 'sumBonus', 'bonusTotal', 'dataBad', 'badTotal', 'sumBad'));
    }

    public function salary()
    {
        $current_user = auth()->user();
        $currentRoleName = $current_user->getRoleNames()[0];
        $user_id = auth()->id();
        $salaries_id = null; // for salary and quality

        if ($currentRoleName == 'editor' || $currentRoleName == 'QA') {
            $salaries_id = $user_id;
            $users = Admin::with(['QAMonthTasks', 'EditorMonthTasks'])->where('id', $user_id)->whereHas('roles', function (Builder $subQuery) {
                $subQuery->whereIn(config('permission.table_names.roles') . '.name', ['QA', 'editor']);
            })->get();

            if ($currentRoleName == 'editor') {
                $conditionAssigner = "editor_id";
            } else {
                $conditionAssigner = 'QA_id';
            }
            $tasks = Tasks::where($conditionAssigner, $user_id)->whereMonth('created_at', Carbon::today())
                ->whereDate('created_at', '!=', Carbon::today())
                ->selectRaw('*, datediff(start_at, end_at) as time_real')
                ->orderBy('created_at', 'desc')->get();

        } else {
            $tasks = Tasks::whereMonth('created_at', Carbon::today())->whereDate('created_at', '!=', Carbon::today())
                ->orderBy('created_at', 'desc')->get();
            $users = Admin::with(['QAMonthTasks', 'EditorMonthTasks'])->whereHas('roles', function (Builder $subQuery) {
                $subQuery->whereIn(config('permission.table_names.roles') . '.name', ['QA', 'editor']);
            })->get();
        }
        foreach ($tasks as $task) {
            $time_spent = 0;
            if ($task->start_at && $task->end_at) {
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $task->start_at);
                $end_at = Carbon::createFromFormat('Y-m-d H:i:s', $task->end_at);
                $time_spent = $end_at->diffInMinutes($start_at);
            }
            $task->timespent = $time_spent;
            $task->average = $task->estimate ? ($time_spent/$task->estimate) : 0;
        }

        // năng lực
        list($salaries, $qualities, $deadline) = $this->getUserSalaries($salaries_id);

        return view('admin.reports.salary', compact('tasks', 'users', 'salaries', 'qualities', 'deadline'));
    }

    public function getUserSalaries($user_id)
    {
        $user = Admin::find($user_id);
        if (!$user) {
            return [[], [], []];
        }
        $currentRoleName = $user->getRoleNames()[0];
        $salaries = [];
        $qualities = [];
        $on_time = 0;
        $late = 0;

        if ($currentRoleName == 'editor') {
            $conditionAssigner = "editor_id";
        } else {
            $conditionAssigner = 'QA_id';
        }
        $tasks = Tasks::where($conditionAssigner, $user->id)->whereMonth('created_at', Carbon::today())
            ->whereDate('created_at', '!=', Carbon::today())
            ->selectRaw('*, datediff(start_at, end_at) as time_real')
            ->orderBy('created_at', 'desc')->get();

        foreach ($tasks as $task) {
            if (!$task->level) {
                continue;
            }

            $time_spent = 0;
            if ($task->start_at && $task->end_at) {
                $start_at = Carbon::createFromFormat('Y-m-d H:i:s', $task->start_at);
                $end_at = Carbon::createFromFormat('Y-m-d H:i:s', $task->end_at);
                $time_spent = $end_at->diffInMinutes($start_at);
            }

            if ($time_spent - ($task->estimate * $task->countRecord) <= 0) {
                $on_time++;
            } else {
                $late++;
            }

            if (array_key_exists($task->level, $salaries)) {
                $salaries[$task->level] += $task->countRecord;
                $qualities[$task->level] += $time_spent;
            } else {
                $salaries[$task->level] = $task->countRecord;
                $qualities[$task->level] = $time_spent;
            }
        }

        foreach ($salaries as $ax => $countRecord) {
            $costRole = 0; //editor
            if ($currentRoleName == 'QA') {
                $costRole = 1;
            } else if($user->is_ctv == 1) {
                $costRole = 2;
            }
            $cost = Admin::COST[$ax][$costRole];
            $salaries[$ax] = [
                'cost' => $cost*$countRecord,
                'countRecord' => $countRecord,
                'unitCost' => $cost,
            ];
        }

        if ($currentRoleName == "editor") {
            foreach ($qualities as $key => &$quantity) {
                $quantity = round($quantity/$salaries[$key]['countRecord'], 2);
            }
        } else {
            $qualities = [];
        }

        return [$salaries, $qualities, [$on_time, $late]];
    }

    public function user_salary($user_id)
    {
        try {
            list($salaries, $qualities, $deadline) = $this->getUserSalaries($user_id);
            return view('admin.reports.sub_salary', compact('salaries', 'qualities', 'deadline'))->render();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

    }

    public function customer()
    {
        $customers_list = Tasks::distinct()->pluck('customer');
        $data = [];
        foreach ($customers_list as $customer) {
            $tasks_amount = Tasks::where('customer',$customer)->count();
            $duplicateCounts = Tasks::where('customer',$customer)->groupBy('case')
            ->select('case', DB::raw('count(*) as count'))
            ->having('count', '>', 1)
            ->first();
            $data[$customer] = [
                'tasks_amount' => $tasks_amount,
                'seperated_task' => $duplicateCounts['case'],
                'seperated_task_amount' => $duplicateCounts['count']
            ];
        }

        return view('admin.reports.customer',compact('data'));
    }

    public function employee()
    {

    }
}
