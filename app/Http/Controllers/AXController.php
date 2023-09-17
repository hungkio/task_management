<?php

namespace App\Http\Controllers;

use App\DataTables\AXDataTable;
use App\Http\Requests\Admin\AXRequest;
use App\Http\Requests\Admin\TaskBulkDeleteRequest;
use App\Http\Requests\Admin\TaskRequest;
use App\Tasks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use App\AX;

class AXController
{
    use AuthorizesRequests;

    public function index(AXDataTable $dataTable)
    {
        $this->authorize('create', Tasks::class);

        return $dataTable->render('admin.ax.index');
    }

    public function create(): View
    {
        $this->authorize('create', Tasks::class);
        return view('admin.ax.create');
    }

    public function store(AXRequest $request)
    {
        $this->authorize('create', Tasks::class);
        $data = $request->all();
        $data['cost'] = json_encode($data['cost']);
        $ax = AX::create($data);
        flash()->success(__('AX ":model" đã được tạo thành công !', ['model' => $ax->name]));

        return intended($request, route('admin.ax.index'));
    }

    public function edit(AX $ax): View
    {
        $cost = json_decode($ax->cost, 1);
        return view('admin.ax.edit', compact('ax', 'cost'));
    }

    public function update(AX $ax, AXRequest $request)
    {
        $data = $request->all();
        $data['cost'] = json_encode($data['cost']);

        $ax->update($data);

        flash()->success(__('AX ":model" đã được cập nhật !', ['model' => $ax->name]));

        return intended($request, route('admin.ax.index'));
    }

    public function destroy(AX $ax)
    {
        $ax->delete();
        return response()->json([
            'status' => true,
            'message' => __('AX đã xóa thành công !'),
        ]);
    }

    public function bulkDelete(TaskBulkDeleteRequest $request)
    {
        $count_deleted = 0;
        $deletedRecord = AX::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $post) {
                $post->delete();
                $count_deleted++;
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" AX thành công ',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }
}
