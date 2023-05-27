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
        $tasks = Tasks::whereMonth('created_at', Carbon::today())->whereDate('created_at', '!=', Carbon::today())->orderBy('created_at', 'desc')->get();
        dd($tasks);
    }

    public function customer()
    {

    }

    public function employee()
    {

    }
}
