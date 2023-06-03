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
    <div class="d-flex mb-4 justify-content-between">
        <button class="dt-button buttons-collection buttons-export btn btn-primary mb-0" onclick="exportMultipleTable(['full'], 'ReportCustomers');"
                type="button" aria-haspopup="true"><span><i class="fal fa-download mr-2"></i>Xuất</span>
        </button>
        <div class="d-flex align-items-center">
            <label class="mb-0 mr-2 lead" for="date">Lọc theo ngày:</label>
            <input type="text" class="datepicker outline-none text-center d-block h-100" name="date">
        </div>
    </div>
    <table class="full w-100" style="display: table;">
        <thead>
        <tr class="border">
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">STT</span></div>
            </th>
            <th class="border bg-blue text-center">
                <div class="relative"><span class="colHeader">Tên Nhân Viên</span></div>
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
            @include('admin.reports.section.customer-table', ['data' => $data])
        </tbody>
    </table>
    
@stop
@push('js')
<script>
  $(document).ready(function(){
    $('.datepicker').datepicker({
      format: 'yyyy-mm-dd' // Định dạng ngày tháng năm
    });
    $('.datepicker').on('change', function() {
      $.ajax({
        url: "{{ route('admin.reports.filter-by-date')}}",
        type: 'POST',
        dataType: 'json',
        data: {
            'date': $(this).val(),
            'category': 'employee'
        },
        complete: function(res) {
            if (res.status == 200) {
                $('.full tbody').html(res.responseText);
            } else {
                showMessage('error', res.responseText);
            }
        }
      })
    })
  });
</script>
@endpush