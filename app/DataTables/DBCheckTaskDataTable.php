<?php

namespace App\DataTables;

use App\DataTables\Core\BaseDatable;
use App\Tasks;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class DBCheckTaskDataTable extends BaseDatable
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
            ->editColumn('customer', fn(Tasks $task) => $task->customer)
            ->editColumn('level', fn(Tasks $task) => $task->level ?? '')
            ->editColumn('countRecord', fn(Tasks $task) => $task->countRecord)
            ->editColumn('status', fn(Tasks $task) => Tasks::STATUS[$task->status])
            ->editColumn('QA_id', fn(Tasks $task) => $task->QA->email ?? '')
            ->editColumn('name', fn(Tasks $task) => $task->name)
            ->editColumn('instruction', fn(Tasks $task) => $task->instruction)
            ->editColumn('dbcheck', fn(Tasks $task) => $task->checker->id == 1 ? "" : ($task->checker->email ?? ''))
            ->editColumn('dbcheck_num', fn(Tasks $task) => $task->dbcheck_num)
            ->editColumn('updated_at', fn(Tasks $task) => formatDate($task->updated_at, 'd/m/y H:i'))
            ->addColumn('action', fn(Tasks $task) => view('admin.dbcheck_tasks._tableAction', compact('task')))
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%$keyword%");
            })
            ->filterColumn('dbcheck', function ($query, $keyword) {
                $query->whereHas('checker', function ($q) use ($keyword) {
                    $q->where('email', 'like', "%$keyword%");
                });
            })
            ->filterColumn('QA_id', function ($query, $keyword) {
                $query->whereHas('QA', function ($q) use ($keyword) {
                    $q->where('email', 'like', "%$keyword%");
                });
            })
            ->orderColumn('name', 'name $1')
            ->rawColumns(['action', 'name', 'status', 'instruction']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Tasks $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Tasks $model)
    {
        $user = auth()->user();
        if (!$user->hasRole('superadmin')) {
            return $model->newQuery()->where('dbcheck', auth()->id())->orWhere('QA_id', auth()->id())->where('dbcheck', '!=', 0);
        }
        return $model->newQuery()->where('dbcheck', '!=', 0);
    }

    protected function getColumns(): array
    {
        return [
            Column::make('customer')->title(__('Mã Khách')),
            Column::make('level')->title(__('Level AX')),
            Column::make('countRecord')->title(__('Original')),
            Column::make('status')->title(__('Trạng thái')),
            Column::make('QA_id')->title(__('QA')),
            Column::make('name')->title(__('Tên nhiệm vụ'))->width('20%'),
            Column::make('instruction')->title(__('Instruction')),
            Column::make('dbcheck')->title(__('DBC')),
            Column::make('dbcheck_num')->title(__('SL DBC')),
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
            Button::make('export')->addClass('btn btn-primary')->text('<i class="fal fa-download mr-2"></i>'.__('Xuất')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>'.__('In')),
        ];
    }

    protected function getBuilderParameters(): array
    {
        $input = "<input style=\"width: 100%\" type=\"text\" placeholder=\"' + title + '\" />";
        $inputDate = "<input class=\"datepicker\" type=\"date\" placeholder=\"' + title + '\" />";
        $selectStatus = "<input class=\"status_filter\" style=\"display: none\" type=\"text\"/>" . "<select style=\"width: 100%\" class=\"p-0 select_status form-control is-valid\" data-width=\"100%\" aria-invalid=\"false\"><option value=\"\" selected=\"\">Trạng thái</option> <option value=\"0\" >Waiting</option><option value=\"1\">Editing</option><option value=\"2\">QA Check</option> <option value=\"3\">Done Reject</option> <option value=\"4\">Reject</option> <option value=\"5\">Ready</option> <option value=\"6\">Finish</option> </select>";

        return [
            'dom' => '<"dt-buttons-full"B><"datatable-header"l><"datatable-scroll-wrap"t><"datatable-footer"ip>',
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
                        if (colIdx== 1 || colIdx== 2 || colIdx== 8|| colIdx== 10) {
                            $(cell).html('');
                            return;
                        }

                        if (colIdx== 9) {
                            $(cell).html('$inputDate');
                        } else if (colIdx== 3) {
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
        return 'dbcheck_tasks_'.date('YmdHis');
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
