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

        .full tbody tr td:nth-child(5) {
            min-width: 300px;
            word-break: break-word;
            white-space: normal;
        }

        .full tbody tr td:nth-child(6) {
            min-width: 300px;
            word-break: break-word;
            white-space: normal;
        }

        .full tbody tr td:nth-child(10) {
            min-width: 100px;
            word-break: break-word;
            white-space: normal;
        }
    </style>
@endpush

@section('page-content')
    <div class="form-group row ml-0">
        <div class="col-lg-2 pl-0">
            <select name="user_report" class="form-control w-auto" data-width="100%">
                <option value="">
                    Lựa chọn user
                </option>
                @if(!empty($users))
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->email }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-lg-3">
            <label class="mb-0 mr-2 lead" for="date">Lọc theo ngày:</label>
            <input type="text" class="datepicker daterange-basic" name="date">
        </div>
    </div>
    <button class="dt-button buttons-collection buttons-export btn btn-primary"
            onclick="exportMultipleTable(['salary', 'quality', 'deadline'], 'ReportSalary');"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
    </button>
    <button class="dt-button buttons-collection buttons-export-all btn btn-primary"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất tất cả user</span>
    </button>
    <a class="all_user_salary d-none" data-href="{{ route('admin.reports.all_user_salary') }}" target="_blank"></a>

    <div class="row ml-0 salary_report">
        @include('admin.reports.sub_salary', ['salaries' => $salaries, 'qualities' => $qualities])
    </div>

    <iframe id="txtArea1" style="display:none"></iframe>
    {{--Full--}}
    <button class="dt-button buttons-collection buttons-export btn btn-primary  mt-3"
            onclick="exportMultipleTable(['full'], 'ReportAllCase');"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
    </button>
    <table class="full w-100" style="display: table;" id="full">
        <thead>
        <tr class="border">
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
{{--            <th class="border bg-blue text-center">--}}
{{--                <div class="relative"><span class="colHeader">Tên nhiệm vụ</span></div>--}}
{{--            </th>--}}
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên job</span></div>
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
                <div class="relative"><span class="colHeader">QA ghi chú</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Bad</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tổng số phút ghi nhận</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Phút/ 1 ảnh</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Excellent</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Editor Time</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">QA Time</span></div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($tasks))
            @foreach($tasks as $key => $task)
                <tr>
                    <td class="border text-center">{{ formatDate($task->created_at) }}</td>
                    <td class="border text-center">{{ $task->customer }}</td>
                    <td class="border text-center">{{ $task->level }}</td>
                    <td class="border text-center">{{ $task->countRecord }}</td>
{{--                    <td class="border text-center">{{ $task->name }}</td>--}}
                    <td class="border text-center">{{ $task->case }}</td>
                    <td class="border text-center">{{ $task->editor->email ?? '' }}</td>
                    <td class="border text-center">{{ $task->QA->email ?? '' }}</td>
                    <td class="border text-center">{{ $task->QA_check_num }}</td>
                    <td class="border text-center">{{ $task->QA_note }}</td>
                    <td class="border text-center">
                        @if($task->redo_note)
                            @foreach(\App\Domain\Admin\Models\Admin::BAD as $bad)
                                @if($bad == @$task->redo_note) {{ $bad }} @endif
                            @endforeach
                        @endif
                    </td>
                    <td class="border text-center">{{ $task->timespent }}</td>
                    <td class="border text-center">{{ $task->average }}</td>
                    <td class="border text-center">{{ $task->excellent ? "Excellent" : "" }}</td>
                    <td class="border text-center">{{ $task->editor_spend }}</td>
                    <td class="border text-center">{{ $task->QA_spend }}</td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

@stop

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script>
        function getUserSalary(user_id, time) {
            $.ajax({
                type: 'get',
                data: {
                  user_id : user_id,
                  time: time
                },
                url: '{{ route("admin.reports.user_salary") }}',
                success: function (res) {
                    if (res.status == 'error') {
                        showMessage('error', res.message)
                    } else {
                        $('.salary_report').html(res)
                    }
                }
            })
        }
        $(function () {
            $('.datepicker').daterangepicker({
                locale: {
                    format: 'YYYY/MM/DD'
                }
            });
            $('.datepicker').on('change', function() {
                let user_id = $('select[name="user_report"]').val()
                let time = $(this).val()
                if (user_id && time) {
                    getUserSalary(user_id, time)
                }
            })
            $('select[name="user_report"]').change(function () {
                let user_id = $(this).val()
                let time = $('.datepicker').val()
                if (user_id && time) {
                    getUserSalary(user_id, time)
                }
            })
            $('.buttons-export-all').click(function () {
                let time = $('.datepicker').val()
                if (time) {
                    let href = $('.all_user_salary').data('href')
                    $('.all_user_salary').attr('href', href + '/?time=' + encodeURIComponent(time))
                    $('.all_user_salary')[0].click()
                }
            })
        })
    </script>
@endpush
