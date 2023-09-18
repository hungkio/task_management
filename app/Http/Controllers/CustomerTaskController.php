<?php

namespace App\Http\Controllers;

use App\AX;
use App\Customers;
use App\DataTables\CustomerTaskDataTable;
use App\Domain\Admin\Models\Admin;
use App\Http\Requests\Admin\TaskRequest;
use App\Tasks;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerTaskController
{
    use AuthorizesRequests;

    public function index(CustomerTaskDataTable $dataTable)
    {
        $this->authorize('customer.view', Tasks::class);

        return $dataTable->render('admin.customer_tasks.index');
    }

    public function edit(Tasks $task): View
    {
        $this->authorize('customer.view', Tasks::class);

        return view('admin.customer_tasks.edit', compact('task'));
    }

    public function update(Tasks $task, Request $request)
    {
        $this->authorize('customer.view', Tasks::class);

        $customer_note = $request->customer_note;

        $task->update(['customer_note' => $customer_note]);

        flash()->success(__('Case ":model" was be updated!', ['model' => $task->case]));

        return intended($request, route('admin.customer_tasks.index'));
    }
}
