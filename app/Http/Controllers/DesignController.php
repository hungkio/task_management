<?php

namespace App\Http\Controllers;

use App\DataTables\DesignDataTable;
use App\Designs;
use App\Tasks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DesignController
{
    use AuthorizesRequests;

    // public function index(DesignDataTable $dataTable)
    // {
    //     $this->authorize('view', Designs::class);
    //     return $dataTable->render('admin.designs.index');
    // }

    public function index()
    {
        $this->authorize('view', Designs::class);
        $tasks = Tasks::all();
        return view('admin.designs.index')->with(['tasks' => $tasks]);
    }
}
