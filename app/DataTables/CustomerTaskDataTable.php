<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Tasks;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class CustomerTaskDataTable extends BaseDatable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('status', fn(Tasks $task) => view('admin.customer_tasks._tableStatus', ['status' => Tasks::CUSTOMER_STATUS[$task->status]]))
            ->editColumn('instruction', fn(Tasks $task) => $task->instruction)
            ->filterColumn('case', function($query, $keyword) {
                $query->where('case', 'like', "%$keyword%");
            })
            ->rawColumns(['instruction']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Tasks $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Tasks $model)
    {
        $customer = auth()->user()->customerName;
        $model = $model->newQuery();

        if ($customer) {
            $now = Carbon::today()->toDateString();
            $past = Carbon::today()->subMonths(1)->toDateString();
            $model->where('customer', $customer->name)->whereDate('created_at','>=', $past)->whereDate('created_at','<=', $now)->latest();
        }

        return $model;
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('case')->title(__('Tên Jobs')),
            Column::make('level')->title(__('Level AX')),
            Column::make('status')->title(__('Status')),
            Column::make('countRecord')->title(__('Original')),
            Column::make('instruction')->title(__('Note')),
        ];
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('export')->addClass('btn btn-primary')->text('<i class="fal fa-download mr-2"></i>' . __('Xuất')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>' . __('In')),
            Button::make('reload')->addClass('btn btn-light')->text('<i class="icon-reset mr-2"></i>'.__('Refresh')),
        ];
    }


    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'customer_tasks_'.date('YmdHis');
    }

}
