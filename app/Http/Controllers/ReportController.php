<?php

namespace App\Http\Controllers;

use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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
                    DB::raw('sum(estimate*QA_check_num) AS "count"'),
                    DB::raw('sum(estimate_QA*QA_check_num) AS "count_QA"'),
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
                $dataBad[$user->email] = $bad;
            }

            $data[$user->email] = $dataDate;
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
            $tasks = Tasks::where($conditionAssigner, $user_id)
                ->whereDate('created_at', '>=', Carbon::today()->subDay(45))
                ->whereDate('created_at', '<=', Carbon::today())
                ->selectRaw('*, datediff(start_at, end_at) as time_real')
                ->orderBy('created_at', 'desc')->get();

        } else {
            $tasks = Tasks::whereDate('created_at', '>=', Carbon::today()->subDay(45))
                ->whereDate('created_at', '<=', Carbon::today())
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
            $task->average = $task->estimate ? gmdate("H:i:s", round($time_spent/$task->estimate, 2)) : 0;
        }

        // năng lực
        $salaries = [];
        $qualities = [];
        $deadline = [];

        return view('admin.reports.salary', compact('tasks', 'users', 'salaries', 'qualities', 'deadline'));
    }

    public function getUserSalaries($user_id, $start, $end)
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
        $tasks = Tasks::where($conditionAssigner, $user->id)->whereDate('created_at', '>=', $start)
            ->whereDate('created_at', '<=', $end)
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

            if ($time_spent - ($task->estimate * $task->editor_check_num) <= 0) {
                $on_time++;
            } else {
                $late++;
            }

            if (array_key_exists($task->level, $salaries)) {
                $salaries[$task->level] += $task->editor_check_num;
                $qualities[$task->level] += $time_spent;
            } else {
                $salaries[$task->level] = $task->editor_check_num;
                $qualities[$task->level] = $time_spent;
            }
        }

        foreach ($salaries as $ax => $editor_check_num) {
            $costRole = 0; //editor
            if ($currentRoleName == 'QA') {
                $costRole = 1;
            } else if($user->is_ctv == 1) {
                $costRole = 2;
            }
            $cost = Admin::COST[$ax][$costRole];
            $salaries[$ax] = [
                'cost' => $cost*$editor_check_num,
                'editor_check_num' => $editor_check_num,
                'unitCost' => $cost,
            ];
        }

        if ($currentRoleName == "editor") {
            foreach ($qualities as $key => &$quantity) {
                $average = $salaries[$key]['editor_check_num'] ? round($quantity*60/$salaries[$key]['editor_check_num'], 2) : 0;
                $quantity = gmdate("H:i:s", $average);
            }
        } else {
            $qualities = [];
        }

        return [$salaries, $qualities, [$on_time, $late]];
    }

    public function user_salary(Request $request)
    {
        $user_id = $request->user_id;
        $date = $request->time;
        list($start, $end) = explode(' - ', $date);
        try {
            list($salaries, $qualities, $deadline) = $this->getUserSalaries($user_id, $start, $end);
            return view('admin.reports.sub_salary', compact('salaries', 'qualities', 'deadline'))->render();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ]);
        }

    }
    public function getCustomerData($date)
    {
        $customers_list = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->distinct()->pluck('customer');
        $data = [];
        foreach ($customers_list as $customer) {
            $tasks_amount = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->where('customer',$customer)->get()->unique('case')->count();
            $duplicateCounts = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->where('customer',$customer)->groupBy('case')
            ->select('case', DB::raw('count(*) as count'))
            ->having('count', '>', 1)
            ->get();
            $seperated_count = 0;
            foreach ($duplicateCounts as $duplicateCount) {
                $seperated_count += $duplicateCount['count'];
            }
            $data[$customer] = [
                'tasks_amount' => $tasks_amount,
                'seperated_task_amount' => $seperated_count
            ];
        }

        return $data;
    }

    public function customer()
    {
        $subday = Carbon::now()->subDay()->format('Y-m-d');

        $data = $this->getCustomerData($subday);

        return view('admin.reports.customer',compact('data'));
    }
    public function getEmployeeData($date)
    {
        $employees = Admin::with(['QAMonthTasks', 'EditorMonthTasks'])->whereHas('roles', function (Builder $subQuery) {
            $subQuery->whereIn(config('permission.table_names.roles') . '.name', ['QA', 'editor']);
        })->get();

        $data = [];

        foreach ($employees as $employee) {
            $id = $employee->id;
            $name = $employee->email;
            $role = $employee->getRoleNames()[0];
            if ($role == 'editor') {
                $tasks_amount = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->where('editor_id',$id)->get()->unique('case')->count();
                $duplicateCounts = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->where('editor_id',$id)->groupBy('case')
                ->select('case', DB::raw('count(*) as count'))
                ->having('count', '>', 1)
                ->get();
                $seperated_count = 0;
                foreach ($duplicateCounts as $duplicateCount) {
                    $seperated_count += $duplicateCount['count'];
                }
                $data[$name] = [
                    'tasks_amount' => $tasks_amount,
                    'seperated_task_amount' => $seperated_count
                ];
            }else{
                $tasks_amount = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->where('qa_id',$id)->get()->unique('case')->count();
                $duplicateCounts = Tasks::whereDate(DB::raw('DATE(created_at)'), date('Y-m-d', strtotime($date)))->where('QA_id',$id)->groupBy('case')
                ->select('case', DB::raw('count(*) as count'))
                ->having('count', '>', 1)
                ->get();
                $seperated_count = 0;
                foreach ($duplicateCounts as $duplicateCount) {
                    $seperated_count += $duplicateCount['count'];
                }
            }
            $data[$name] = [
                'tasks_amount' => $tasks_amount,
                'seperated_task_amount' => $seperated_count
            ];
        }
        return $data;
    }
    public function employee()
    {
        $subday = Carbon::now()->subDay()->format('Y-m-d');

        $data = $this->getEmployeeData($subday);

        return view('admin.reports.employee',compact('data'));
    }

    public function getTasksByDate(Request $request)
    {
        $date = $request->input('date');
        $category = $request->input('category');
        if ($category == 'customer') {
            $data = $this->getCustomerData(date('Y-m-d', strtotime($date)));
        }else{
            $data = $this->getEmployeeData(date('Y-m-d', strtotime($date)));
        }

        return view('admin.reports.section.customer-table',compact('data'))->render();
    }
}
