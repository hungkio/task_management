<?php

namespace App\Repositories;

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
        return $this->_model->with(['editor', 'QA'])->where('status', $status)->where($condition)->orderBy('updated_at', 'DESC')->get();
    }

    public function getTodayTasks($status, $condition = [])
    {
        return $this->_model->with(['editor', 'QA'])->whereDate('created_at', Carbon::today())->where('status', $status)->where($condition)->orderBy('updated_at', 'DESC')->get();
    }

}
