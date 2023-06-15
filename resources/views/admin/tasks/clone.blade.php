@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $task->name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.tasks._form', [
        'url' =>  route('admin.tasks.store'),
        'task' => $task ?? new \App\Tasks,
        'QAs' => $QAs,
        'dbcs' => $dbcs,
        'editors' => $editors,
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js" integrity="sha512-9WciDs0XP20sojTJ9E7mChDXy6pcO0qHpwbEJID1YVavz2H6QBz5eLoDD8lseZOb2yGT8xDNIV7HIe1ZbuiDWg==" crossorigin="anonymous"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\TaskRequest', '#post-form'); !!}
@endpush


