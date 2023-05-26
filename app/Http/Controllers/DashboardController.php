<?php

namespace App\Http\Controllers;

use App\Domain\Admin\Models\Admin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Tasks;

class DashboardController
{
    use AuthorizesRequests;

    public function index(Tasks $tasks)
    {
        $user = auth()->user();
        $user->update([
            'is_online' => 1
        ]);
        $roleName = $user->getRoleNames()[0];
        $user_id = auth()->id();
        $conditionAssigner = "";

        if($roleName == 'editor') {
            $conditionAssigner = "editor_id";
        } else if ($roleName == 'QA') {
            $conditionAssigner = 'QA_id';
        }

        $tasks_waiting = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::WAITING)->get();
        $tasks_todo = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::TODO)->get();

        $this->assignEditor();
        if ($conditionAssigner) {
            $tasks_editing = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::EDITING)->get();
            $tasks_testing = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::TESTING)->get();
            $tasks_done = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::DONE)->get();
            $tasks_rejected = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::REJECTED)->get();
            $tasks_todo = $tasks->where($conditionAssigner, $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::TODO)->get();
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
            'tasks_todo' => $tasks_todo
        ]);
    }

    public function assignEditor()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $user_id = auth()->id();
        $tasks = new Tasks;

        $tasks_rejected = $tasks->where('editor_id', $user_id)->whereDate('created_at', Carbon::today())->where('status', Tasks::REJECTED)->get();
        $tasks_ready = $tasks->where('editor_id', $user_id)->whereDate('created_at', Carbon::today())->whereIn('status', [Tasks::EDITING, Tasks::TODO])->get();
        $tasks_editing = $tasks->whereDate('created_at', Carbon::today())->where('status', Tasks::WAITING)->whereNotNull('level')->whereNotNull('estimate')->get();

        $today = Carbon::today()->format("Y-m-d");
        $from = strtotime($today . ' 08:00:00');
        $to = strtotime($today . ' 23:59:00');
        if(time() >= $from && time() <= $to) { // in working time
            if ($roleName == 'editor' && $tasks_ready->isEmpty() && $tasks_rejected->isEmpty()) {
                foreach ($tasks_editing as $key => $value) {
                    if (!str_contains($user->level, $value->level)) {
                        $tasks_editing->forget($key);
                        continue;
                    }
                    if ($value->redo) {
                        $black_list = json_decode($value->redo);
                        if (in_array($user_id, $black_list)) {
                            $tasks_editing->forget($key);
                        }
                    }else {
                        break;
                    }
                }
                if (!$tasks_editing->isEmpty()) {
                    $tasks_editing = $tasks_editing->first();
                    $tasks_editing->update([
                        'editor_id' => $user_id,
                        'status' => Tasks::TODO,
                    ]);
                    return $tasks_editing;
                }
            }
        }

    }

    public function assignQA($taskId)
    {
        $task = Tasks::findOrFail($taskId);
        if ($task->QA_id) {
            $QA = Admin::where('id', $task->QA_id)->where('is_online', 1)->first();
        }else {
            // have lowest number of task
            $QA = Admin::with(['roles'])->withCount('QATasks')->whereHas('roles', function (Builder $subQuery) {
                $subQuery->where(config('permission.table_names.roles').'.name', 'QA');
            })->where('is_online', 1)->orderBy('q_a_tasks_count')->first();
        }

        if ($QA) {
            $task->update([
                'QA_id' => $QA->id,
                'status' => Tasks::TESTING,
                'QA_start' => date("Y-m-d H:i")
            ]);
        }
        return [
            'task' => $task,
            'QA' => $QA
        ];
    }

    public function showPopup($id)
    {
        $task = Tasks::find($id);
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $editor = $this->getUser($task->editor_id);
        $QA = $this->getUser($task->QA_id);
        return view('admin.dashboards.popup')->with([
            'task' => $task,
            'roleName' => $roleName,
            'editor' => $editor,
            'QA' => $QA,
        ]);
    }

    public function savePopup(Request $request ,$id)
    {
        $task = Tasks::find($id);
        $inputData = $request->all();
        if (isset($inputData['redo']) && $inputData['redo'] == 'on') {
            if ($task->redo) {
                $black_list = json_decode($task->redo);
                $black_list = array_merge($black_list, [$task->editor_id]);
            }else {
                $black_list = [$task->editor_id];
            }
            $task->update([
                'editor_id' => null,
                'status' => Tasks::WAITING,
                'redo' => json_encode($black_list)
            ]);
            unset($inputData['redo']);
        }
        $task->update($inputData);
        return redirect()->route('admin.dashboards');
    }
    public function getUser($id)
    {
        $user = Admin::find($id);

        return $user;
    }
}
