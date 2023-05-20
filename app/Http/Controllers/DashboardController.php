<?php

namespace App\Http\Controllers;

use App\Domain\Admin\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Tasks;

class DashboardController
{
    use AuthorizesRequests;

    public function index(Tasks $tasks)
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $user_id = auth()->id();
        $conditionAssigner = "";

        if($roleName == 'editor') {
            $conditionAssigner = "editor_id";
        } else if ($roleName == 'QA') {
            $conditionAssigner = 'QA_id';
        }

        $tasks_waiting = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::WAITING)->get();

        //auto assign to editor when no bug
        $this->assignEditor();
//        $this->assignQA(10);

        if ($conditionAssigner) {
            $tasks_editing = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::EDITING)->get();
            $tasks_testing = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::TESTING)->get();
            $tasks_done = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::DONE)->get();
            $tasks_rejected = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::REJECTED)->get();
        } else {
            $tasks_editing = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::EDITING)->get();
            $tasks_testing = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::TESTING)->get();
            $tasks_done = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::DONE)->get();
            $tasks_rejected = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::REJECTED)->get();
        }

        return view('admin.dashboards.index')->with([
            'tasks_waiting' => $tasks_waiting,
            'tasks_editing' => $tasks_editing,
            'tasks_testing' => $tasks_testing,
            'tasks_done' => $tasks_done,
            'tasks_rejected' => $tasks_rejected,
        ]);
    }

    public function assignEditor()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $user_id = auth()->id();
        $tasks = new Tasks;

        $tasks_rejected = $tasks->where('editor_id', $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::REJECTED)->get();
        $tasks_editing = $tasks->where('editor_id', $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::EDITING)->get();
        $tasks_waiting = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::WAITING);

        if ($roleName == 'editor' && $tasks_editing->isEmpty() && $tasks_rejected->isEmpty()) {
            $level = $user->level ?? 0;

            $tasks_editing = $tasks_waiting->where('level', '<=', $level)->first();

            if ($tasks_editing) {
                $tasks_editing->update([
                    'editor_id' => $user_id,
                    'status' => Tasks::EDITING
                ]);
            }
        }

        return $tasks_editing;
    }

    public function assignQA($taskId)
    {
        $task = Tasks::findOrFail($taskId);

        // have lowest number of task
        $QA = Admin::with(['roles'])->withCount('QATasks')->whereHas('roles', function (Builder $subQuery) {
            $subQuery->where(config('permission.table_names.roles').'.name', 'QA');
        })->orderBy('q_a_tasks_count')->first();

        if ($QA) {
            $task->update([
                'QA_id' => $QA->id,
                'status' => Tasks::TESTING,
            ]);
        }

        return $task;
    }
}
