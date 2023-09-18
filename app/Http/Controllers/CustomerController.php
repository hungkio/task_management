<?php

namespace App\Http\Controllers;

use App\AX;
use App\DataTables\CustomerDataTable;
use App\Http\Requests\Admin\TaskBulkDeleteRequest;
use App\Http\Requests\Admin\CustomerRequest;
use App\Tasks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use App\Customers;

class CustomerController
{
    use AuthorizesRequests;

    public function index(CustomerDataTable $dataTable)
    {
        $this->authorize('create', Tasks::class);

        return $dataTable->render('admin.customers.index');
    }

    public function create(): View
    {
        $this->authorize('create', Tasks::class);
        $AX = AX::all();
        return view('admin.customers.create', ['AX' => $AX]);
    }

    public function store(CustomerRequest $request)
    {
        $this->authorize('create', Tasks::class);
        $data = $request->all();
        $files = $request->file('style');
        $styles = [];
        if($request->hasFile('style'))
        {
            foreach ($files as $file) {
                $path = 'customers/' . $file->getClientOriginalName();
                $file->storeAs('',$path);
                $styles[] = $path;
            }
        }

        if ($styles) {
            $data['styles'] = json_encode($styles);
        }
        $customer = Customers::create($data);

        flash()->success(__('Customer ":model" đã được tạo thành công !', ['model' => $customer->name]));

        return intended($request, route('admin.customers.index'));
    }

    public function edit(Customers $customer): View
    {
        $AX = AX::all();
        return view('admin.customers.edit', compact('customer', 'AX'));
    }

    public function update(Customers $customer, CustomerRequest $request)
    {
        $data = $request->all();
        $files = $request->file('style');
        $styles = [];
        if($request->hasFile('style'))
        {
            foreach ($files as $file) {
                $path = 'customers/' . $file->getClientOriginalName();
                $file->storeAs('',$path);
                $styles[] = $path;
            }
        }

        if ($styles) {
            $data['styles'] = json_encode($styles);
        }
        $customer->update($data);

        flash()->success(__('Customer ":model" đã được cập nhật !', ['model' => $customer->name]));

        return intended($request, route('admin.customers.index'));
    }

    public function destroy(Customers $customer)
    {
        $customer->delete();
        return response()->json([
            'status' => true,
            'message' => __('Customer đã xóa thành công !'),
        ]);
    }

    public function bulkDelete(TaskBulkDeleteRequest $request)
    {
        $count_deleted = 0;
        $deletedRecord = Customers::whereIn('id', $request->input('id'))->get();
        foreach ($deletedRecord as $post) {
                $post->delete();
                $count_deleted++;
        }
        return response()->json([
            'status' => true,
            'message' => __('Đã xóa ":count" customer thành công ',
                [
                    'count' => $count_deleted,
                    'count_fail' => count($request->input('id')) - $count_deleted,
                ]),
        ]);
    }
}
