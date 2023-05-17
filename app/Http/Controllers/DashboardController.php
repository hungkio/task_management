<?php

namespace App\Http\Controllers;

use App\DataTables\DashboardDataTable;
use App\Designs;
use App\Domain\Admin\Models\Admin;
use App\Http\Requests\Admin\DashboardRequest;
use App\Products;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DashboardController
{
    use AuthorizesRequests;

    public function index(DashboardDataTable $dataTable)
    {
        return $dataTable->render('admin.dashboards.index');
    }
}
