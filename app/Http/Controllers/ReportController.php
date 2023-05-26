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
            $dates = collect();
            for ($i = 0; $i < Carbon::now()->daysInMonth; $i++) {
                $date = Carbon::now()->startOfMonth()->addDays($i)->format('Y-m-d');
                $dates->put($date, 0);
            }
            $roleName = $user->getRoleNames()[0];
            $conditionAssigner = "";
            if ($roleName == 'editor') {
                $conditionAssigner = "editor_id";
            } else if ($roleName == 'QA') {
                $conditionAssigner = 'QA_id';
            }
            $counts = Tasks::where($conditionAssigner, $user->id)->where([['created_at', '>=', $dates->keys()->first()]])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE( created_at ) as date'),
                    DB::raw('sum(estimate) AS "count"'),
                ]);
            $dataDate = [];
            foreach ($dates as $key => $date) {
                $totalTime = $counts->where('date', $key)->first()->count ?? 0;
                $dataDate[$key] = $totalTime;
            }
            $data[$user->fullName] = $dataDate;
        }

        return view('admin.reports.month', compact('data'));
    }

    public function customer()
    {

    }

    public function employee()
    {

    }

    public function salary()
    {

    }
}
