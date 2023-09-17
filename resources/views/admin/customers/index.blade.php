@extends('admin.layouts.master')

@section('title', __('Khách hàng'))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    <!-- Main charts -->
    <x-card>
        {{$dataTable->table()}}
    </x-card>


@stop

@push('js')
    {{$dataTable->scripts()}}
    <script>
        @can('customers.create')
        $('.buttons-create').removeClass('d-none')
        @endcan
        @can('customers.delete')
        $('.bg-danger').removeClass('d-none')
        @endcan
    </script>
@endpush
