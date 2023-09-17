<?php

namespace App\Http\Controllers;

use App\DataTables\CustomerTaskDataTable;
use App\Tasks;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerTaskController
{
    use AuthorizesRequests;

    public function index(CustomerTaskDataTable $dataTable)
    {
        $this->authorize('customer.view', Tasks::class);

        return $dataTable->render('admin.customer_tasks.index');
    }

}
