<?php

namespace App\Http\Controllers;

use App\DataTables\DBCheckTaskDataTable;
use App\Domain\Admin\Models\Admin;
use App\Tasks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DBCheckTaskController
{
    use AuthorizesRequests;

    public function index(DBCheckTaskDataTable $dataTable)
    {
        $this->authorize('dbchecks.view', Tasks::class);

        return $dataTable->render('admin.dbcheck_tasks.index');
    }

    public function destroy(Tasks $task)
    {
        $task->update([
            'dbcheck' => 0
        ]);

        return response()->json([
            'status' => true,
            'message' => __('Case :name đã xóa double check !', ['name' => $task->name]),
        ]);
    }

    public function update(Tasks $task, Request $request)
    {
        $data = $request->all();

        $task->update($data);

        flash()->success(__('Case ":model" đã được cập nhật !', ['model' => $task->name]));

        return intended($request, route('admin.dbcheck_tasks.index'));
    }

    public function edit(Tasks $task): View
    {
        $dbcs = Admin::whereIn('email', Admin::DBC_PEOPLE)->get();

        return view('admin.dbcheck_tasks.edit', compact('task', 'dbcs'));
    }
}
