@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $task->name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.tasks._form', [
        'url' =>  route('admin.tasks.update', $task),
        'task' => $task ?? new \App\Tasks,
        'QAs' => $QAs,
        'editors' => $editors,
        'dbcs' => $dbcs,
        'AX' => $AX,
        'method' => 'PUT'
    ])
@stop
@push('js')
    <script>
        $('.form-check-input-styled').uniform();
        $('.select2').select2({
            placeholder: "{{ __('-- Vui lòng chọn --') }}",
            allowClear: true
        });
    </script>
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\TaskRequest', '#post-form'); !!}
@endpush

