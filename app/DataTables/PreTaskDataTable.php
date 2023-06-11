<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\PreTasks;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PreTaskDataTable extends BaseDatable
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
            ->editColumn('customer', fn (PreTasks $task) => $task->customer)
            ->editColumn('level', fn (PreTasks $task) => $task->level ?? '')
            ->editColumn('countRecord', fn (PreTasks $task) => $task->countRecord)
            ->editColumn('name', fn (PreTasks $task) => $task->name)
            ->editColumn('case', fn (PreTasks $task) => $task->case)
            ->editColumn('path', fn (PreTasks $task) => $task->path)
            ->editColumn('updated_at', fn (PreTasks $task) => formatDate($task->updated_at, 'd/m/Y H:i:s'))
            ->addColumn('action', 'admin.pre_tasks._tableAction')
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->orderColumn('name', 'name $1')
            ->rawColumns(['action', 'name', 'status']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\PreTasks $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(PreTasks $model)
    {
        return $model->newQuery();
    }

    protected function getColumns(): array
    {
        return [
            Column::checkbox(''),
            Column::make('customer')->title(__('Tên Khách')),
            Column::make('level')->title(__('Level AX')),
            Column::make('countRecord')->title(__('Original')),
            Column::make('name')->title(__('Tên nhiệm vụ'))->width('20%'),
            Column::make('case')->title(__('Tên Jobs')),
            Column::make('path')->title(__('Đường dẫn')),
            Column::make('updated_at')->title(__('Cập nhật')),
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
            Button::make('export')->addClass('btn btn-primary')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
//            Button::make('selected')->addClass('btn bg-teal-400 import')
//                ->text('<i class="icon-compose mr-2"></i>'.__('Import')
//                ),
            Button::make('bulkDelete')->addClass('btn btn-danger d-none')->text('<i class="fal fa-trash-alt mr-2"></i>'.__('Xóa')),
        ];
    }

    protected function getBuilderParameters(): array
    {
        $input = "<input style=\"width: 100%\" type=\"text\" placeholder=\"' + title + '\" />";
        $inputDate = "<input class=\"datepicker\" type=\"date\" placeholder=\"' + title + '\" />";
        return [
            'dom' => '<"dt-buttons-full"B><"datatable-header"l><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            'order' => [6, 'desc'],
            "initComplete" => "function () {
                    var api = this.api();

                    // For each column
                    api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                        var title = $(cell).text();
                        if (colIdx== 0 || colIdx== 2 || colIdx== 3|| colIdx== 6) {
                            $(cell).html('');
                            return;
                        }

                        if (colIdx== 7) {
                            $(cell).html('$inputDate');
                        } else {
                            $(cell).html('$input');
                        }
                        $('.select_status').change(function () {
                            $('input.status_filter').val($(this).val()).trigger('change')
                        })
                        // On every keypress in this input
                        $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                        .off('keyup change')
                        .on('change', function (e) {
                            // Get the search value
                            $(this).attr('title', $(this).val());
                            var regexr = '{search}'; //$(this).parents('th').find('select').val();

                            var cursorPosition = this.selectionStart;
                            // Search the column for that value
                            api
                            .column(colIdx)
                            .search(
                                this.value != ''
                                    ? regexr.replace('{search}', this.value)
                                    : '',
                                this.value != '',
                                this.value == ''
                            )
                            .draw();
                        })
                        .on('keyup', function (e) {
                            e.stopPropagation();
                            var cursorPosition = this.selectionStart;

                            $(this).trigger('change');
                            $(this)
                            .focus()[0]
                            .setSelectionRange(cursorPosition, cursorPosition);
                        });
                    });
                }"
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'pre_tasks_'.date('YmdHis');
    }

//    protected function buildExcelFile()
//    {
//        $this->request()->merge(['length' => -1]);
//        $source = app()->call([$this, 'query']);
//        $source = $this->applyScopes($source);
//
////        return new TaskExportHandler($source->whereDate('created_at', Carbon::today())->get());
//        return new TaskExportHandler($source->get());
//    }

    public function printPreview()
    {
        $this->request()->merge(['length' => -1]);
        $source = app()->call([$this, 'query']);
        $source = $this->applyScopes($source);
        $data = $source->get();
        return view('admin.pre_tasks.print', compact('data'));
    }
}
