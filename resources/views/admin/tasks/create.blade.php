@extends('admin.layouts.master')

@section('title', __('Tạo sản phẩm'))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@section('page-content')
    @include('admin.tasks._form', [
        'url' =>  route('admin.tasks.store'),
        'task' => new \App\Tasks,
        'QAs' => $QAs,
        'editors' => $editors,
        'dbcs' => $dbcs,
        'AX' => $AX,
    ])
@stop
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css">
    <style>
        div input[type='checkbox'] {
            position: relative;
            top: 10px;
        }
    </style>
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('.datepicker').datetimepicker({
            format:'Y-m-d H:i',
        });
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

