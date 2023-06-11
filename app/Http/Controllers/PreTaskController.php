<?php

namespace App\Http\Controllers;

use App\DataTables\PreTaskDataTable;
use App\Domain\Admin\Models\Admin;
use App\Http\Requests\Admin\TaskBulkDeleteRequest;
use App\Http\Requests\Admin\TaskRequest;
use App\PreTasks;
use App\Tasks;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;

class PreTaskController
{
    use AuthorizesRequests;

    public function index(PreTaskDataTable $dataTable)
    {
        $this->authorize('create', Tasks::class);

        return $dataTable->render('admin.pre_tasks.index');
    }

    public function create(): View
    {
        $this->authorize('create', Tasks::class);
        return view('admin.pre_tasks.create');
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('create', Tasks::class);
        $data = $request->all();
        $preTask = PreTasks::create($data);

        flash()->success(__('Case ":model" đã được tạo thành công !', ['model' => $preTask->title]));

        return intended($request, route('admin.pre_tasks.index'));
    }

    public function edit(PreTasks $preTask): View
    {
        return view('admin.pre_tasks.edit', compact('preTask'));
    }

    public function update(PreTasks $preTask, TaskRequest $request)
    {
        $data = $request->all();

        $preTask->update($data);

        flash()->success(__('Case ":model" đã được cập nhật !', ['model' => $preTask->name]));

        return intended($request, route('admin.pre_tasks.index'));
    }

    public function destroy(PreTasks $preTask)
    {
        $preTask->delete();
        return response()->json([
            'status' => true,
            'message' => __('Case đã xóa thành công !'),
        ]);
    }

    public function bulkDelete(TaskBulkDeleteRequest $request)
    {
        $count_deleted = 0;
        $deletedRecord = PreTasks::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $post) {
                $post->delete();
                $count_deleted++;
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" case thành công ',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }
}
