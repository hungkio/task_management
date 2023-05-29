<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        @yield('title')
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf_token">
    <link rel="icon" href="{{ setting('store_favicon') ? \Storage::disk('local')->url(setting('store_favicon')) : '' }}" type="image/gif" sizes="16x16">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/global_assets/css/icons/icomoon/styles.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/bootstrap_limitless.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/components.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/colors.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/theme.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->
    @stack('css')
</head>
<body class="@yield('body-class')" data-spy="scroll" data-target=".sidebar-component-right">
@yield('navbar')
@yield('content')
<script>
    window.Config = {
        dateFormat: 'd-m-Y H:i',
        baseUrl: '{{ url('/') }}',
        version: '{{ config('ecc.app_version') }}',
        adminPrefix: '/admin',
        csrf: '{{ csrf_token() }}'
    }

    window.Lang = {
        confirm_delete: "{{ __('Bạn có chắc chắn muốn xóa ?') }}",
        oh_no: "{{ __('Ôi không !') }}",
        system: "{{ __('Hệ thống') }}",
        success: "{{ __('Thành công !') }}",
        confirm: "{{ __('Xác nhận') }}",
        yes: "{{ __('Có') }}",
        no: "{{ __('Không') }}",
        create: "{{ __('Tạo') }}",
        rename: "{{ __('Đổi tên') }}",
        edit: "{{ __('Chỉnh sửa') }}",
        remove: "{{ __('Xóa') }}",
    }
</script>
<script src="{{ asset('/backend/global_assets/js/main/jquery.min.js') }}"></script>
<script src="{{ asset('/backend/global_assets/js/main/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/backend/global_assets/js/main/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('/backend/global_assets/js/plugins/loaders/blockui.min.js') }}"></script>
<script src="{{ asset('/backend/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
@stack('vendor-js')
<script src="{{ asset('/backend/js/app.js') }}"></script>
<script src="{{ asset('/backend/js/custom.js') }}"></script>
<script>
    function exportMultipleTable(elementClasses = [], reportName, canvasId = null) {
        var html = ""
        elementClasses.forEach(function(item) {
            html += fnExcelReport(item)
            html +='<br>'
        });

        //canvas
        if (canvasId) {
            let canvas = $('#' + canvasId + ' canvas');
            let pngUrl = canvas[0].toDataURL(); // PNG is the default
            html += '<table border=\'2px\'>\n' +
                '    <tr>\n' +
                '        <th class="border bg-blue text-center">\n' +
                '            <img width="1000" height="300" src="' + pngUrl + '" alt="Pie Chart" />\n' +
                '        </th>\n' +
                '    </tr>\n' +
                '</table>';
        }

        html = html.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        html = html.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(html);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
            return (sa)
        } else {
            var result = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            var link = document.createElement("a");
            document.body.appendChild(link);
            link.download = reportName + ".xls"; //You need to change file_name here.
            link.href = result;
            link.click();
        }                 //other browser not tested on IE 11

    }
    function fnExcelReport(elementClass) {
        var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
        var j = 0;
        tab = $('.' + elementClass)[0] // class of table

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        }

        tab_text = tab_text + "</table>";

        return tab_text
    }
    // function fnExcelReport(elementClass) {
    //     var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
    //     var textRange;
    //     var j = 0;
    //     tab = $('.' + elementClass)[0] // id of table
    //
    //     for (j = 0; j < tab.rows.length; j++) {
    //         tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
    //         //tab_text=tab_text+"</tr>";
    //     }
    //
    //     tab_text = tab_text + "</table>";
    //     tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    //     tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
    //     tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    //
    //     var ua = window.navigator.userAgent;
    //     var msie = ua.indexOf("MSIE ");
    //
    //     if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    //     {
    //         txtArea1.document.open("txt/html", "replace");
    //         txtArea1.document.write(tab_text);
    //         txtArea1.document.close();
    //         txtArea1.focus();
    //         sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
    //     } else                 //other browser not tested on IE 11
    //         sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
    //
    //     return (sa);
    // }

</script>
@stack('js')
</body>
</html>
