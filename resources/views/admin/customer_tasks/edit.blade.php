@extends('admin.layouts.master')

@section('title', __('Editing :model', ['model' => $task->case]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.customer_tasks._form', [
        'url' =>  route('admin.customer_tasks.update', $task),
        'task' => $task ?? new \App\Tasks,
        'method' => 'PUT'
    ])
@stop
@push('js')
    <script>
        $('.form-check-input-styled').uniform();
        $('.select2').select2({
            placeholder: "{{ __('-- Select --') }}",
            allowClear: true
        });
    </script>
    <script src="{{ asset('backend/js/editor-admin.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\TaskRequest', '#post-form'); !!}
@endpush

