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
            ->editColumn('case', fn (Tasks $task) => $task->case)
            ->editColumn('customer', fn (Tasks $task) => $task->customer)
            ->editColumn('level', fn (Tasks $task) => $task->level ?? '')
            ->editColumn('countRecord', fn (Tasks $task) => $task->countRecord)
            ->editColumn('status', fn (Tasks $task) => Tasks::STATUS[$task->status])
            ->editColumn('editor_id', fn (Tasks $task) => $task->editor->fullName ?? '')
            ->editColumn('QA_id', fn (Tasks $task) => $task->QA->fullName ?? '')
            ->editColumn('created_at', fn (Tasks $task) => formatDate($task->created_at, 'd/m/Y H:i:s'))
            ->addColumn('action', 'admin.tasks._tableAction')
            ->filterColumn('name', function($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->filterColumn('editor_id', function($query, $keyword) {
                $query->whereHas('editor', function($q) use ($keyword) {
                    $q->orWhere('first_name', 'like', "%$keyword%")
                        ->orWhere('last_name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('QA_id', function($query, $keyword) {
                $query->whereHas('QA', function($q) use ($keyword) {
                    $q->orWhere('first_name', 'like', "%$keyword%")
                        ->orWhere('last_name', 'like', "%$keyword%");
                });
            })
            ->orderColumn('name', 'name $1')
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
            Column::make('name')->title(__('Tên nhiệm vụ'))->width('20%'),
            Column::make('case')->title(__('Tên Jobs')),
            Column::make('customer')->title(__('Mã Khách')),
            Column::make('level')->title(__('Level AX')),
            Column::make('countRecord')->title(__('Original')),
            Column::make('status')->title(__('Trạng thái')),
            Column::make('editor_id')->title(__('Editor')),
            Column::make('QA_id')->title(__('QA')),
            Column::make('created_at')->title(__('Thời gian tạo')),
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
            Button::make('selected')->addClass('btn bg-teal-400 import')
                ->text('<i class="icon-compose mr-2"></i>'.__('Import')
                ),
            Button::make('bulkDelete')->addClass('btn btn-danger d-none')->text('<i class="fal fa-trash-alt mr-2"></i>'.__('Xóa')),
        ];
    }

    protected function getBuilderParameters(): array
    {
        $input = "<input style=\"width: 100%\" type=\"text\" placeholder=\"' + title + '\" />";
        $inputDate = "<input class=\"datepicker\" type=\"date\" placeholder=\"' + title + '\" />";
        $selectStatus = "<input class=\"status_filter\" style=\"display: none\" type=\"text\"/>" . "<select style=\"width: 100%\" class=\"p-0 select_status form-control is-valid\" data-width=\"100%\" aria-invalid=\"false\"> <option value=\"0\" selected=\"\">Waiting</option><option value=\"1\">Editing</option><option value=\"2\">QA Check</option> <option value=\"3\">Done Reject</option> <option value=\"4\">Reject</option> <option value=\"5\">Ready</option> <option value=\"6\">Finish</option> </select>";
        return [
            'order' => [9, 'desc'],
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
                        if (colIdx== 0 || colIdx== 2 || colIdx== 1|| colIdx== 5 || colIdx== 10) {
                            $(cell).html('');
                            return;
                        }

                        if (colIdx== 9) {
                            $(cell).html('$inputDate');
                        } else if (colIdx== 6) {
                            $(cell).html('$selectStatus');
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
        return 'tasks_'.date('YmdHis');
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
