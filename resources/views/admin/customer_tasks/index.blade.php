@extends('admin.layouts.master')

@section('title', __('Danh sách load'))
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
        tbody tr td:nth-child(7) {
            min-width: 300px;
            word-break: break-word;
            white-space: normal;
        }
        tbody tr td:nth-child(2) {
            min-width: 300px;
            word-break: break-word;
            white-space: normal;
        }
        .done {
            padding: 5px;
            border: 1px solid;
            border-radius: 5px;
            text-align: center;
            background-color: #458B00;
            color: white;
            font-weight: 600;
        }
        .testing {
            padding: 5px;
            border: 1px solid;
            border-radius: 5px;
            text-align: center;
            background-color: #ebc334;
            color: white;
            font-weight: 600;
        }
        .editing {
            padding: 5px;
            border: 1px solid;
            border-radius: 5px;
            text-align: center;
            background-color: #1890ff;
            color: white;
            font-weight: 600;
        }
        .dataTable thead tr:first-child {
            background: #370f0f;
            color: white;
            font-size: 16px;
        }
        .dataTable thead tr:first-child th {
            text-align: center;
        }
    </style>
@endpush

@section('page-content')
    <x-card>
        {{$dataTable->table()}}
    </x-card>

@stop

@push('js')
    {{$dataTable->scripts()}}
    <script>
        $(function () {
            $('.dataTable').addClass('table-bordered')
            $('#CustomerTaskDataTable thead ')
                .append('<tr role="row" class="filters"><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>');
        })
    </script>
@endpush
