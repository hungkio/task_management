{{--salary--}}
<table class="col-3 full mr-3" style="display: table;">
    <thead>
    <tr class="border">
        <th class="border bg-blue text-center">
            <div class="relative"><span class="colHeader">Mã sản phẩm</span></div>
        </th>
        <th class="border bg-blue text-center">
            <div class="relative"><span class="colHeader">Số lượng công việc</span></div>
        </th>
        <th class="border bg-blue text-center">
            <div class="relative"><span class="colHeader">Đơn giá</span></div>
        </th>
        <th class="border bg-blue text-center">
            <div class="relative"><span class="colHeader">Thành tiền</span></div>
        </th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($salaries))
        @foreach($salaries as $key => $salary)
            <tr>
                <td class="border text-center">{{ $key }}</td>
                <td class="border text-center">{{ $salary['countRecord'] }}</td>
                <td class="border text-center">{{ number_format($salary['unitCost']) }}</td>
                <td class="border text-center">{{ number_format($salary['cost']) }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

{{--quality--}}
<table class="col-3 full" style="display: table;">
    <thead>
    <tr class="border">
        <th class="border bg-blue text-center" colspan="2">
            <div class="relative"><span class="colHeader">Bảng năng lực nhân viên</span></div>
        </th>
    </tr>
    <tr class="border">
        <th class="border text-danger text-center">
            <div class="relative"><span class="colHeader">Mã SP</span></div>
        </th>
        <th class="border text-danger text-center">
            <div class="relative"><span class="colHeader">TB phút / ảnh</span></div>
        </th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($qualities))
        @foreach($qualities as $key => $quality)
            <tr>
                <td class="border text-center">{{ $key }}</td>
                <td class="border text-center">{{ $quality }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
