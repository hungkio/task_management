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
    <button class="dt-button buttons-collection buttons-export btn btn-primary" onclick="exportMultipleTable(['full', 'bonus', 'bad'], 'ReportMonth', 'chart-pie');"
            type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
    </button>
    <table class="full w-100" style="display: table;">
        <thead>
        <tr class="border">
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">STT</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên NV</span></div>
            </th>
            <th class="border bg-blue text-center" colspan="{{ count(@reset($data)) ?? 0 }}">
                <div class="relative"><span class="colHeader">Ngày</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Sum</span></div>
            </th>
        </tr>
        <tr class="border">
            <th class="border bg-purple text-center" colspan="2">
                <div class="relative">Kết quả</div>
            </th>
            @if(!empty($data))
                @if(!empty(reset($data)))
                    @foreach(reset($data) as $key => $count)
                        <th class="border bg-purple text-center" style="width: 40px">
                            <div class="relative"><span class="colHeader">{{ $key+1 }}</span></div>
                        </th>
                    @endforeach
                @endif
            @endif
            <th class="border bg-purple">
                <div class="relative"></div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($data))
            @php($row = 1)
            @foreach($data as $key => $user)
                @php($sum = 0)
                <tr>
                    <td class="border text-center">{{ $row }}</td>
                    <td class="border name">{{ $key }}</td>
                    @if(!empty($user))
                        @foreach($user as $count)
                            <td class="border text-center @if($count >= 600) bg-orange @endif">{{ $count }}</td>
                            @php($sum += $count)
                        @endforeach
                    @endif
                    <td class="border text-center sum">{{ formatNumber($sum) }}</td>
                </tr>
                @php($row += 1)
            @endforeach
            <tr>
                <td class="border"></td>
                <td class="border">{{ formatNumber($sumTotal) }}</td>
                @if(!empty($data))
                    @if(!empty(reset($data)))
                        @foreach(reset($data) as $key => $count)
                            <td class="border text-center">{{ @$dataTotal[$key] }}</td>
                        @endforeach
                    @endif
                @endif
                <td class="border text-center">{{ formatNumber($sumTotal) }}</td>
            </tr>
        @endif
        </tbody>
    </table>

    {{--Bonus--}}
    <table class="bonus w-100 mt-3" style="display: table;">
        <thead>
        <tr class="border">
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">STT</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên NV</span></div>
            </th>
            <th class="border bg-blue text-center" colspan="{{ count(@reset($data)) ?? 0 }}">
                <div class="relative"><span class="colHeader">Ngày</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Sum</span></div>
            </th>
        </tr>
        <tr class="border">
            <th class="border bg-purple text-center" colspan="2">
                <div class="relative">Bonus</div>
            </th>
            @if(!empty($data))
                @if(!empty(reset($data)))
                    @foreach(reset($data) as $key => $count)
                        <th class="border bg-purple text-center" style="width: 40px">
                            <div class="relative"><span class="colHeader">{{ $key+1 }}</span></div>
                        </th>
                    @endforeach
                @endif
            @endif
            <th class="border bg-purple">
                <div class="relative"></div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($data))
            @php($row = 1)
            @foreach($data as $key => $user)
                @php($sum = 0)
                <tr>
                    <td class="border text-center">{{ $row }}</td>
                    <td class="border name">{{ $key }}</td>
                    @if(!empty($user))
                        @foreach($user as $count)
                            <td class="border text-center @if($count >= 600) bg-orange @endif">{{ ($count >= 600) ? formatNumber(100000) : 0 }}</td>
                            @php($sum += (($count >= 600) ? 100000 : 0))
                        @endforeach
                    @endif
                    <td class="border text-center sum">{{ formatNumber($sum) }}</td>
                </tr>
                @php($row += 1)
            @endforeach
            <tr>
                <td class="border"></td>
                <td class="border">{{ formatNumber($sumBonus) }}</td>
                @if(!empty($data))
                    @if(!empty(reset($data)))
                        @foreach(reset($data) as $key => $count)
                            <td class="border text-center">{{ formatNumber($bonusTotal[$key]) }}</td>
                        @endforeach
                    @endif
                @endif
                <td class="border text-center">{{ formatNumber($sumBonus) }}</td>
            </tr>
        @endif
        </tbody>
    </table>

    {{--Bad--}}
    <table class="bad w-100 mt-3" style="display: table;">
        <thead>
        <tr class="border">
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">STT</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên NV</span></div>
            </th>
            <th class="border bg-blue text-center" colspan="{{ count(@reset($dataBad)) ?? 0 }}">
                <div class="relative"><span class="colHeader">Ngày</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Sum</span></div>
            </th>
        </tr>
        <tr class="border">
            <th class="border bg-purple text-center" colspan="2">
                <div class="relative">Bad Editing</div>
            </th>
            @if(!empty($dataBad))
                @if(!empty(reset($dataBad)))
                    @foreach(reset($dataBad) as $key => $badMoney)
                        <th class="border bg-purple text-center" style="width: 40px">
                            <div class="relative"><span class="colHeader">{{ $key+1 }}</span></div>
                        </th>
                    @endforeach
                @endif
            @endif
            <th class="border bg-purple">
                <div class="relative"></div>
            </th>
        </tr>
        </thead>
        <tbody>
        @if(!empty($dataBad))
            @php($row = 1)
            @foreach($dataBad as $key => $user)
                @php($sum = 0)
                <tr>
                    <td class="border text-center">{{ $row }}</td>
                    <td class="border name">{{ $key }}</td>
                    @if(!empty($user))
                        @foreach($user as $badMoney)
                            <td class="border text-center">{{ number_format($badMoney) }}</td>
                            @php($sum += $badMoney)
                        @endforeach
                    @endif
                    <td class="border text-center sum">{{ formatNumber($sum) }}</td>
                </tr>
                @php($row += 1)
            @endforeach
            <tr>
                <td class="border"></td>
                <td class="border">{{ formatNumber($sumBonus) }}</td>
                @if(!empty($dataBad))
                    @if(!empty(reset($dataBad)))
                        @foreach(reset($dataBad) as $key => $badMoney)
                            <td class="border text-center">{{ formatNumber($badTotal[$key]) }}</td>
                        @endforeach
                    @endif
                @endif
                <td class="border text-center">{{ formatNumber($sumBad) }}</td>
            </tr>
        @endif
        </tbody>
    </table>

    <div class="mt-3 border" id="chart-pie" style="width: 100%;height:500px;"></div>
@stop

@push('js')
    <script src="{{ asset('/backend/js/echarts.min.js') }}"></script>
    <script>
        $(function () {
            var chartDom = document.getElementById('chart-pie');
            var myChart = echarts.init(chartDom);
            var option;
            var data = [['name', 'value']];

            $('.full tbody tr').each(function (key, val) {
                let name = $(val).find('.name').text()
                let sum = $(val).find('.sum').text()
                let row = [name, parseInt(sum)];
                data.push(row);
            })

            setTimeout(function () {
                option = {
                    legend: {},
                    tooltip: {
                        trigger: 'axis',
                        showContent: false
                    },
                    dataset: {
                        source: data
                    },
                    xAxis: {type: 'category'},
                    yAxis: {gridIndex: 0},
                    grid: {top: '30%'},
                    series: [
                        {
                            type: 'pie',
                            id: 'pie',
                            radius: '60%',
                            center: ['50%', '50%'],
                            emphasis: {
                                focus: 'self'
                            },
                            label: {
                                formatter: '{b}({d}%)'
                            },
                            encode: {
                                itemName: 'name',
                                value: 'value',
                                tooltip: 'value'
                            }
                        }
                    ]
                };
                myChart.setOption(option);
            });
            option && myChart.setOption(option);
        })
    </script>
@endpush
