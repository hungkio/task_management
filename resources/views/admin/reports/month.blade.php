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
    <button class="dt-button buttons-collection buttons-export btn btn-primary" onclick="exportImg();"
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
    <script src="https://unpkg.com/exceljs/dist/exceljs.min.js"></script>
    <script src="{{ asset('/backend/js/echarts.min.js') }}"></script>
    <script>
        function base64ToArrayBuffer(base64) {
            var base64WithoutScheme = base64.replace(/^data:image\/(png|jpeg|jpg);base64,/, '');
            var binaryString = window.atob(base64WithoutScheme);
            var len = binaryString.length;
            var bytes = new Uint8Array(len);
            for (var i = 0; i < len; ++i) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes.buffer;
        }

        function saveByteArray(buffer, fileName) {
            var blob = new Blob([buffer], {type: 'application/octet-stream'});
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function tableToData(table) {
            var rows = [];
            var headers = [];

            var headerCells = table.querySelectorAll('thead tr');
            headerCells.forEach(function (row) {
                var rowData = [];
                var cells = row.querySelectorAll('th');
                cells.forEach(function (cell) {
                    let colspan = cell.getAttribute('colspan');
                    if (colspan) {
                        rowData.push(cell.innerText);
                        for (let i = 0; i < colspan - 1; i++) {
                            rowData.push("");
                        }
                    } else {
                        rowData.push(cell.innerText);
                    }
                });

                headers.push(rowData);
            });

            var bodyRows = table.querySelectorAll('tbody tr');
            bodyRows.forEach(function (row) {
                var rowData = [];
                var cells = row.querySelectorAll('td');
                cells.forEach(function (cell) {
                    rowData.push(cell.innerText);
                });
                rows.push(rowData);
            });

            return {columns: headers, rows: rows};
        }

        function getExcelCellRef(row, column) {
            var columnLabel = String.fromCharCode(65 + column - 1);
            return columnLabel + row;
        }

        function exportImg() {
            var htmlTables = [
                '<table>' + $('.full').html() + '</table>',
                '<table>' + $('.bonus').html() + '</table>',
                '<table>' + $('.bad').html() + '</table>',
            ];

            var workbook = new ExcelJS.Workbook();
            var worksheet = workbook.addWorksheet('Data');
            let currentRow = 1

            htmlTables.forEach(function (htmlTable, index) {
                var tempContainer = document.createElement('div');
                tempContainer.innerHTML = htmlTable;

                var tableElement = tempContainer.querySelector('table');
                var tableData = tableToData(tableElement);

                const headerRowStyle = {
                    font: { bold: true, color: { argb: 'FFFFFFFF' } },
                    fill: {type: 'pattern', pattern: 'solid', fgColor: {argb: 'FF1890FF'}},
                    border: {
                        style: 'thin',
                        color: { argb: 'FF000000' }
                    }
                };

                const dataRowStyle = {
                    fill: {type: 'pattern', pattern: 'solid', fgColor: {argb: 'FFFFFFFF'}}
                };

                //header
                tableData.columns.forEach(rowData => {
                    var mergeStartCell = null;
                    var mergeEndCell = null;
                    rowData.forEach((header, columnIndex) => {
                        const cell = worksheet.getCell(currentRow, columnIndex + 1);
                        cell.value = header;
                        cell.style = headerRowStyle;
                        cell.alignment = { vertical: 'middle', horizontal: 'center' };

                        // merge cell empty with previous
                        if (cell.value !== '') {
                            if (mergeStartCell && mergeEndCell) {
                                worksheet.mergeCells(mergeStartCell.address+ ':' + mergeEndCell.address);
                                mergeStartCell = null;
                                mergeEndCell = null;
                            }
                            mergeStartCell = cell;
                        } else {
                            mergeEndCell = cell;
                        }
                    });
                    currentRow++
                });

                //data
                tableData.rows.forEach((rowData, rowIndex) => {
                    rowData.forEach((data, columnIndex) => {
                        const cell = worksheet.getCell(currentRow, columnIndex + 1);
                        cell.value = data;
                        cell.style = dataRowStyle;
                        cell.alignment = { vertical: 'middle', horizontal: 'center' };
                    });
                    currentRow++
                });
                currentRow++
            });

            // Apply borders to all cells
            const borderStyle = {
                style: 'thin',
                color: { argb: 'FF000000' }
            };
            worksheet.eachRow(row => {
                row.eachCell(cell => {
                    cell.border = {
                        top: borderStyle,
                        left: borderStyle,
                        bottom: borderStyle,
                        right: borderStyle
                    };
                });
            });

            worksheet.columns.forEach(column => {
                column.width = 15;
                column.bestFit = true;
            });

            // for img
            let canvas = $('#chart-pie canvas');
            let base64Image = canvas[0].toDataURL(); // PNG is the default

            // Convert base64 to ArrayBuffer
            var arrayBuffer = base64ToArrayBuffer(base64Image);

            // Load the image from ArrayBuffer
            var imageId = workbook.addImage({
                buffer: arrayBuffer,
                extension: 'png',
            });

            // Add the image to the worksheet
            worksheet.addImage(imageId, {
                tl: {col: 1, row: currentRow + 2},
                ext: {width: 1500, height: 450},
            });

            workbook.xlsx.writeBuffer().then(function (buffer) {
                saveByteArray(buffer, 'ReportMonth.xlsx');
            });

        }


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
