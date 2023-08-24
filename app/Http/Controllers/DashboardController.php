<?php

namespace App\Http\Controllers;

use App\Domain\Admin\Models\Admin;
use App\Repositories\DashboardRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Tasks;

class DashboardController
{
    use AuthorizesRequests;

    private $repository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->repository = $dashboardRepository;
    }

    public function index(Tasks $tasks, Request $request)
    {
        $this->authorize('dashboards.view', Tasks::class);
        $inputFilter = $request->input('filter-by-user');
        $userFound = null;
        $conditionFinish = [];
        $conditionWait = [];
        $condition = [];
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $user_id = $user->id;
        $conditionAssigner = "";

        if ($roleName == 'editor') {
            $conditionAssigner = "editor_id";
        } else if ($roleName == 'QA') {
            $conditionAssigner = 'QA_id';
        }

        if ($inputFilter) {
            $userFound = Admin::where('email', $inputFilter)->first();
        }

        $user->update([
            'is_online' => 1
        ]);

        if ($inputFilter) {
            $conditionFinish = ['customer' => $inputFilter];
            if ($userFound) {
                $userFoundRole = $userFound->getRoleNames()[0];
                $userFoundId = $userFound->id;
                $conditionWait = [$userFoundRole . '_id' => $userFoundId];
            } else {
                $conditionWait = ['customer' => $inputFilter];
            }
        } else {
            if ($conditionAssigner) {
                $conditionFinish = [$conditionAssigner => $user_id];
            }
        }

        $this->assignEditor();

        if ($conditionAssigner) {
            if ($inputFilter) {
                if ($userFound) {
                    $userFoundRole = $userFound->getRoleNames()[0];
                    $userFoundId = $userFound->id;
                    $condition = [$conditionAssigner => $user_id, $userFoundRole . '_id' => $userFoundId];
                } else {
                    $condition = [$conditionAssigner => $user_id, 'customer' => $inputFilter];
                }
            } else {
                $condition = [$conditionAssigner => $user_id];
            }
        } else {
            if ($inputFilter) {
                if ($userFound) {
                    $userFoundRole = $userFound->getRoleNames()[0];
                    $userFoundId = $userFound->id;
                    $condition = [$userFoundRole . '_id' => $userFoundId];
                } else {
                    $condition = ['customer' => $inputFilter];
                }
            }
        }

        $tasks_editing = $this->repository->getTasks(Tasks::EDITING, $condition);
        $tasks_testing = $this->repository->getTasks(Tasks::TESTING, $condition);
        $tasks_done = $this->repository->getTasks(Tasks::DONE, $condition);
        $tasks_rejected = $this->repository->getTasks(Tasks::REJECTED, $condition);
        $tasks_todo = $this->repository->getTasks(Tasks::TODO, $condition);
        $tasks_finished = $this->repository->getTodayTasks(Tasks::FINISH, $conditionFinish);
        $tasks_waiting = $this->repository->getTasks(Tasks::WAITING, $conditionWait);

        return view('admin.dashboards.index')->with([
            'tasks_waiting' => $tasks_waiting,
            'tasks_editing' => $tasks_editing,
            'tasks_testing' => $tasks_testing,
            'tasks_done' => $tasks_done,
            'tasks_rejected' => $tasks_rejected,
            'tasks_todo' => $tasks_todo,
            'tasks_finished' => $tasks_finished,
            'input_filter' => $inputFilter
        ]);
    }

    public function assignEditor()
    {
        $user = auth()->user();
        $roleName = $user->getRoleNames()[0];
        $user_id = auth()->id();
        $tasks = new Tasks;

        $tasks_rejected = $tasks->where('editor_id', $user_id)->where('status', Tasks::REJECTED)->get();
        $tasks_ready = $tasks->where('editor_id', $user_id)->whereIn('status', [Tasks::EDITING, Tasks::TODO])->get();
        $tasks_editing = $tasks->where('status', Tasks::WAITING)->whereNotNull('level')->whereNotNull('estimate')->orderBy('priority', 'DESC')->get();

        $today = Carbon::today()->format("Y-m-d");
//        $from = strtotime($today . ' 07:00:00');
//        $to = strtotime($today . ' 23:59:00');
//        if (time() >= $from && time() <= $to) { // in working time
        if ($user->lock_task == 0 && $roleName == 'editor' && $tasks_ready->isEmpty() && $tasks_rejected->isEmpty()) {
            $user_level = $user->level ? explode(',', $user->level) : [];
            foreach ($tasks_editing as $key => $value) {
                if (!in_array($value->level, $user_level)) {
                    $tasks_editing->forget($key);
                    continue;
                }
                if ($value->redo) {
                    $black_list = json_decode($value->redo);
                    if (in_array($user_id, $black_list)) {
                        $tasks_editing->forget($key);
                    }
                } else {
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
//        }

    }

    public function assignQA($taskId)
    {
        $task = Tasks::findOrFail($taskId);
        if ($task->QA_id) {
            $QA = Admin::where('id', $task->QA_id)->where('is_online', 1)->first();
        } else {
            // have lowest number of task
            $QAs = Admin::with(['roles'])->withCount('QATasks')->whereHas('roles', function (Builder $subQuery) {
                $subQuery->where(config('permission.table_names.roles') . '.name', 'QA');
            })->where('is_online', 1)->where('lock_task', 0)->orderBy('q_a_tasks_count')->get();
            $QA = null;
            // check QA level
            foreach ($QAs as $qa) {
                if ($qa->level) {
                    $levels = explode(',', $qa->level);
                    if (in_array($task->level, $levels)) {
                        $QA = $qa;
                        break;
                    }
                }
            }
        }
        if ($QA) {
            if ($task->status == Tasks::EDITING) {
                $endTime = date("Y-m-d H:i");
                $task->update([
                    'end_at' => $endTime
                ]);
            }
            $task->update([
                'QA_id' => $QA->id,
                'status' => Tasks::TESTING,
                'QA_start' => date("Y-m-d H:i"),
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

    public function savePopup(Request $request, $id)
    {
        $task = Tasks::find($id);
        $inputData = $request->all();
        if (isset($inputData['redo']) && $inputData['redo'] == 'on') {
            if ($task->redo) {
                $black_list = json_decode($task->redo);
                $black_list = array_merge($black_list, [$task->editor_id]);
            } else {
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
        return back();
    }

    public function getUser($id)
    {
        $user = Admin::find($id);

        return $user;
    }

    public function updateStatus(Request $request)
    {
        $taskId = $request->input('id');
        $processStatus = $request->input('status');
        if ($request->input('status')) {
            $confirm = $request->input('confirm');
        }

        $task = Tasks::findOrFail($taskId);
        if ($processStatus == Tasks::TESTING && $task->status == Tasks::EDITING) {
            $endTime = date("Y-m-d H:i");
            $task->update([
                'end_at' => $endTime
            ]);
        }
        $task->status = $processStatus;
        if ($processStatus == Tasks::REJECTED) {
            $task->excellent = Tasks::NOT_EXCELLENT;
        }

        if ($processStatus == Tasks::DONE) {
            $endTime = date("Y-m-d H:i");
            $task->update([
                'QA_end' => $endTime
            ]);
        }
        if (isset($confirm)) {
            $task->start_at = date("Y-m-d H:i");
        }
        $task->save();

        // Phản hồi JSON với thông tin cập nhật thành công
        return response()->json(['message' => 'Cập nhật trạng thái thành công'], 200);
    }

    public function checkOnline($id)
    {
        return $this->getUser($id)->is_online;
    }
}
