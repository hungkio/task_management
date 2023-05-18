<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Tasks;

class DashboardController
{
    use AuthorizesRequests;

    public function index(Tasks $tasks)
    {
        $tasks_waiting = $tasks->where('status', 0)->get();
        $tasks_editing = $tasks->where('status', 1)->get();
        $tasks_testing = $tasks->where('status', 2)->get();
        $tasks_done = $tasks->where('status', 3)->get();
        $tasks_rejected = $tasks->where('status', 4)->get();

        return view('admin.dashboards.index')->with([
            'tasks_waiting' => $tasks_waiting,
            'tasks_editing' => $tasks_editing,
            'tasks_testing' => $tasks_testing,
            'tasks_done' => $tasks_done,
            'tasks_rejected' => $tasks_rejected,
        ]);
    }
}
