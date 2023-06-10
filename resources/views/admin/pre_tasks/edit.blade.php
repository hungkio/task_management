@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $preTask->name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.pre_tasks._form', [
        'url' =>  route('admin.pre_tasks.update', $preTask),
        'task' => $preTask ?? new \App\PreTasks,
        'method' => 'PUT'
    ])
@stop
@push('js')
    <script>
        $('.form-check-input-styled').uniform();
    </script>
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\TaskRequest', '#post-form'); !!}
@endpush

