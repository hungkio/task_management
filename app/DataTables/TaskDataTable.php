<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\DataTables\Export\TaskExportHandler;
use App\Tasks;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class TaskDataTable extends BaseDatable
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
            ->editColumn('name', fn (Tasks $task) => $task->name)
            ->editColumn('date', fn (Tasks $task) => $task->date)
            ->editColumn('month', fn (Tasks $task) => $task->month)
            ->editColumn('customer', fn (Tasks $task) => $task->customer)
            ->editColumn('status', fn (Tasks $task) => Tasks::STATUS[$task->status])
            ->editColumn('editor_id', fn (Tasks $task) => $task->editor->fullName ?? '')
            ->editColumn('QA_id', fn (Tasks $task) => $task->editor->fullName ?? '')
            ->editColumn('created_at', fn (Tasks $task) => formatDate($task->created_at))
            ->addColumn('action', 'admin.tasks._tableAction')
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orderColumn('name', 'title $1')
            ->rawColumns(['action', 'name', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Tasks $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Tasks $model)
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('id')->title(__('STT'))->data('DT_RowIndex')->searchable(false),
            Column::make('name')->title(__('Tên case'))->width('20%'),
            Column::make('date')->title(__('Ngày tháng'))->width('20%'),
            Column::make('month')->title(__('Tháng'))->width('20%'),
            Column::make('customer')->title(__('Khách hàng'))->width('20%'),
            Column::make('status')->title(__('Trạng thái'))->width('20%'),
            Column::make('editor_id')->title(__('Editor'))->width('20%'),
            Column::make('QA_id')->title(__('QA'))->width('20%'),
            Column::make('created_at')->title(__('Thời gian tạo'))->searchable(false),
            Column::computed('action')
                ->title(__('Tác vụ'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('create')->addClass('btn btn-success d-none')->text('<i class="fal fa-plus-circle mr-2"></i>'.__('Tạo mới')),
            Button::make('bulkDelete')->addClass('btn btn-danger d-none')->text('<i class="fal fa-trash-alt mr-2"></i>'.__('Xóa')),
            Button::make('export')->addClass('btn btn-primary')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
        ];
    }

    protected function getBuilderParameters(): array
    {
        return [
            'order' => [5, 'desc'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'brand_'.date('YmdHis');
    }

    protected function buildExcelFile()
    {
        $this->request()->merge(['length' => -1]);
        $source = app()->call([$this, 'query']);
        $source = $this->applyScopes($source);

        return new TaskExportHandler($source->get());
    }

    public function printPreview()
    {
        $this->request()->merge(['length' => -1]);
        $source = app()->call([$this, 'query']);
        $source = $this->applyScopes($source);
        $data = $source->get();
        return view('admin.tasks.print', compact('data'));
    }
}
