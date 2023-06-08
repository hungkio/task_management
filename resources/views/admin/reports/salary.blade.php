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
    <div class="form-group row ml-0">
        <div class="col-lg-9 pl-0">
            <select name="user_report" class="form-control w-auto" data-width="100%">
                <option value="">
                    Lựa chọn user
                </option>
                @if(!empty($users))
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->fullName }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <button class="dt-button buttons-collection buttons-export btn btn-primary" onclick="exportMultipleTable(['salary', 'quality', 'deadline'], 'ReportSalary');"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
    </button>
    <div class="row ml-0 salary_report">
        @include('admin.reports.sub_salary', ['salaries' => $salaries, 'qualities' => $qualities])
    </div>

    <iframe id="txtArea1" style="display:none"></iframe>
    {{--Full--}}
    <button class="dt-button buttons-collection buttons-export btn btn-primary  mt-3" onclick="exportMultipleTable(['full'], 'ReportAllCase');"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
    </button>
    <table class="full w-100" style="display: table;" id="full">
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
            $('select[name="user_report"]').change(function () {
                let user_id = $(this).val()
                if (user_id) {
                    $.ajax({
                        type: 'get',
                        url: '{{ route("admin.reports.user_salary") }}/' + user_id,
                        success: function (res) {
                            if (res.status == 'error') {
                                showMessage('error', res.message)
                            } else {
                                $('.salary_report').html(res)
                            }
                        }
                    })
                }
            })
        })
    </script>
@endpush
