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
    <iframe id="txtArea1" style="display:none"></iframe>
    {{--Full--}}
    <button class="dt-button buttons-collection buttons-export btn btn-primary" onclick="exportMultipleTable(['full'], 'ReportCustomers');"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
    </button>
    <table class="full w-100" style="display: table;">
        <thead>
        <tr class="border">
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">STT</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên Khách Hàng</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Số Lượng Cases</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Số Lượng Cases Tách</span></div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($data))
            @php($row = 1)
            @php($sumCases = 0)
            @php($sumCasesTach = 0)
            @foreach($data as $customer => $value)
                @php($sumCases += $value['tasks_amount'])
                @php($sumCasesTach += $value['seperated_task_amount'])
                <tr>
                    <td class="border text-center">{{ $row }}</td>
                    <td class="border name">{{ $customer }}</td>
                    <td class="border text-center">{{ $value['tasks_amount'] }}</td>
                    <td class="border text-center">{{ $value['seperated_task_amount'] }}</td>
                </tr>
                @php($row += 1)
            @endforeach
            <tr>
                <td class="border"></td>
                <td class="border">Total</td>
                <td class="border text-center">{{ $sumCases }}</td>
                <td class="border text-center">{{ $sumCasesTach }}</td>
            </tr>
        @endif
        </tbody>
    </table>
    
@stop