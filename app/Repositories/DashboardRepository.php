<?php

namespace App\Repositories;

use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Carbon\Carbon;

class DashboardRepository extends BaseRepository
{
    public function getModel()
    {
        return Tasks::class;
    }

    public function getTasks($status, $condition = [])
    {
        $data = $this->_model->with(['editor', 'QA'])->where('status', $status)->where($condition)->orderBy('updated_at', 'DESC')->get();
        $data = $this->handleRedoDisplay($data);

        return $data;
    }

    public function getTodayTasks($status, $condition = [])
    {
        $data = $this->_model->with(['editor', 'QA'])->whereDate('created_at', Carbon::today())->where('status', $status)->where($condition)->orderBy('updated_at', 'DESC')->get();
        $data = $this->handleRedoDisplay($data);
        return $data;
    }

    public function handleRedoDisplay($data) {
        foreach ($data as $task) {
            if ($task->redo) {
                $blacklist = json_decode($task->redo);
                $users = Admin::whereIn('id', $blacklist)->get('email');
                $users_arr = [];
                foreach ($users as $user) {
                    $users_arr[] = $user->email;
                }

                if ($users_arr) {
                    $task->redo = implode(', ', $users_arr);
                }
            }
        }

        return $data;
    }

}
