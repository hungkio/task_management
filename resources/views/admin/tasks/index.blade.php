@extends('admin.layouts.master')

@section('title', __('Sản phẩm'))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@push('css')
    <style>
        @media (max-width: 767.98px) {
            .btn-danger {
                margin-left: 0 !important;
            }
        }

        @media (width: 320px) {
            .btn-danger {
                margin-left: .625rem !important;
            }
        }
        tbody tr td:first-child {
            min-width: 300px;
            word-break: break-all;
            white-space: normal;
        }
        tbody tr td:nth-child(2) {
            min-width: 300px;
            word-break: break-all;
            white-space: normal;
        }
    </style>
@endpush

@section('page-content')
    <x-card>
        {{$dataTable->table()}}
    </x-card>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" method="POST" action="{{ route('admin.tasks.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header  text-center">
                    <h4 class="modal-title" id="exampleModalLabel">Import case</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label text-lg-right" for="redo">
                            File excel:
                        </label>
                        <div class="col-lg-9">
                            <input  type="file" name="file" id="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@stop

@push('js')
    {{$dataTable->scripts()}}
    <script>
        $(document).on('change', '#select_status', function () {
            var status = $(this).val();
            var url = $(this).attr('data-url');
            confirmAction('Bạn có muốn thay đổi trạng thái ?', function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        data: {
                            'status': status
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status == true) {
                                showMessage('success', res.message);
                            } else {
                                showMessage('error', res.message);
                            }
                            window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                        },
                    });
                } else {
                    window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                }
            });
        });
        @can('products.create')
        $('.buttons-create').removeClass('d-none')
        @endcan
        @can('products.delete')
        $('.btn-danger').removeClass('d-none')
        @endcan
        @can('products.update')
        $('.btn-warning').removeClass('d-none')
        @endcan

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()

            $('#TaskDataTable thead ')
                .append('<tr role="row" class="filters"><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>');

            $('.import').click(function () {
                $('#exampleModal').modal('show')
            })
            $('.select_status').change(function () {
                $('input.status_filter').val($(this).val()).trigger('change')
            })
            // $('.datepicker').datepicker({
            //     dateFormat: 'yy-mm-dd',
            //     altFormat: 'dd.mm.yy',
            //     altField: $(this)
            // });
        })
    </script>
@endpush
