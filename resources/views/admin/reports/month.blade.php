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
                    @php($day = 1)
                    @foreach(reset($data) as $count)
                        <th class="border bg-purple text-center" style="width: 40px">
                            <div class="relative"><span class="colHeader">{{ $day }}</span></div>
                        </th>
                        @php($day += 1)
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
                        @php($day = 1)
                        @foreach($user as $count)
                            <td class="border text-center @if($count >= 600) bg-orange @endif">{{ $count }}</td>
                            @php($day += 1)
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
                        @php($day = 0)
                        @foreach(reset($data) as $count)
                            <td class="border text-center">{{ $dataTotal[$day] }}</td>
                            @php($day += 1)
                        @endforeach
                    @endif
                @endif
                <td class="border text-center">{{ formatNumber($sumTotal) }}</td>
            </tr>
        @endif
        </tbody>
    </table>

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
                    @php($day = 1)
                    @foreach(reset($data) as $count)
                        <th class="border bg-purple text-center" style="width: 40px">
                            <div class="relative"><span class="colHeader">{{ $day }}</span></div>
                        </th>
                        @php($day += 1)
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
                        @php($day = 1)
                        @foreach($user as $count)
                            <td class="border text-center @if($count >= 600) bg-orange @endif">{{ ($count >= 600) ? formatNumber(100000) : 0 }}</td>
                            @php($day += 1)
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
                        @php($day = 0)
                        @foreach(reset($data) as $count)
                            <td class="border text-center">{{ formatNumber($bonusTotal[$day]) }}</td>
                            @php($day += 1)
                        @endforeach
                    @endif
                @endif
                <td class="border text-center">{{ formatNumber($sumBonus) }}</td>
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
                console.log(data)
                option = {
                    legend: {},
                    tooltip: {
                        trigger: 'axis',
                        showContent: false
                    },
                    dataset: {
                        source: data
                    },
                    xAxis: { type: 'category' },
                    yAxis: { gridIndex: 0 },
                    grid: { top: '30%' },
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