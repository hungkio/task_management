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
            ->editColumn('status', fn(Tasks $task) => view('admin.customer_tasks._tableStatus', ['status' => Tasks::CUSTOMER_STATUS[$task->status]]))
            ->editColumn('customer_note', fn(Tasks $task) => $task->customer_note)
            ->editColumn('created_at', fn(Tasks $task) => formatDate($task->created_at, 'd/m/Y H:i:s'))
            ->addColumn('action', fn(Tasks $task) => view('admin.customer_tasks._tableAction', compact('task')))
            ->filterColumn('case', function($query, $keyword) {
                $query->where('case', 'like', "%$keyword%");
            })
            ->filterColumn('status', function($query, $status) {
                if ($status == 0) {
                    $query->orWhere('status', 0)->orWhere('status', 1)->orWhere('status', 4)->orWhere('status', 5);
                } else if ($status == 1) {
                    $query->where('status', 2);
                } else {
                    $query->orWhere('status', 3)->orWhere('status', 6);

                }
            })
            ->rawColumns(['action']);
        ;
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
            Column::make('customer')->title(__('CUSTOMER NAME'))->className('text-center'),
            Column::make('case')->title(__('JOB NAME')),
            Column::make('level')->title(__('LEVEL AX'))->className('text-center'),
            Column::make('status')->title(__('STATUS'))->className('text-center'),
            Column::make('countRecord')->title(__('ORIGINAL'))->className('text-center'),
            Column::make('QA_check_num')->title(__('DONE'))->className('text-center'),
            Column::make('customer_note')->title(__('NOTE')),
            Column::make('created_at')->title(__('DATE'))->className('text-center'),
            Column::computed('action')
                ->title(__('EDIT'))
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    protected function getTableButton(): array
    {
        return [
            Button::make('export')->addClass('btn btn-primary')->text('<i class="fal fa-download mr-2"></i>' . __('Export Excel')),
            Button::make('print')->addClass('btn bg-primary')->text('<i class="fal fa-print mr-2"></i>' . __('Print')),
            Button::make('reload')->addClass('btn btn-light')->text('<i class="icon-reset mr-2"></i>'.__('Refresh')),
        ];
    }

    protected function getBuilderParameters(): array
    {
        $input = "<input style=\"width: 100%\" type=\"text\" placeholder=\"' + title + '\" />";
        $inputDate = "<input class=\"datepicker\" type=\"date\" placeholder=\"' + title + '\" />";
        $selectStatus = "<input class=\"status_filter\" style=\"display: none\" type=\"text\"/>" . "<select style=\"width: 100%\" class=\"p-0 select_status form-control is-valid\" data-width=\"100%\" aria-invalid=\"false\"><option value=\"\" selected=\"\">Status</option> <option value=\"0\" >Editing</option><option value=\"1\">Testing</option><option value=\"2\">Done</option></select>";
        return [
            'dom' => '<"dt-buttons-full"B><"datatable-header"l><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            'order' => [7, 'desc'],
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
                        if ( colIdx== 8) {
                            $(cell).html('');
                            return;
                        }

                        if (colIdx== 7) {
                            $(cell).html('$inputDate');
                        } else if (colIdx== 3) {
                            $(cell).html('$selectStatus');
                        }else {
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
        return 'customer_tasks_'.date('YmdHis');
    }

}
