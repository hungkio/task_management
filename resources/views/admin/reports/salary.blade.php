@extends('admin.layouts.master')

@section('title', __('Sản phẩm'))
@section('page-header')
    <x-page-header>
        {{--        {{ Breadcrumbs::render() }}--}}
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
    </style>
@endpush

@section('page-content')
    {{--Full--}}
    <table class="full w-100" style="display: table;">
        <thead>
        <tr class="border">
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">ID</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">N/T</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên KH</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">AX</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Num</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên công việc</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Người thực hiện</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Người kiểm tra</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Kết quả QA</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tổng số giờ ghi nhận</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Phút/ 1 ảnh</span></div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($tasks))
            @foreach($tasks as $key => $task)
                <tr>
                    <td class="border text-center">{{ $task->id }}</td>
                    <td class="border text-center">{{ formatDate($task->created_at) }}</td>
                    <td class="border text-center">{{ $task->customer }}</td>
                    <td class="border text-center">{{ $task->level }}</td>
                    <td class="border text-center">{{ $task->countRecord }}</td>
                    <td class="border text-center">{{ $task->name }}</td>
                    <td class="border text-center">{{ $task->editor->fullName ?? '' }}</td>
                    <td class="border text-center">{{ $task->QA->fullName ?? '' }}</td>
                    <td class="border text-center">{{ $task->QA_check_num }}</td>
                    <td class="border text-center">{{ $task->timespent }}</td>
                    <td class="border text-center">{{ $task->average }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@stop

@push('js')
    <script>
        $(function () {
        })
    </script>
@endpush
