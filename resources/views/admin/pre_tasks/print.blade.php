<!DOCTYPE html>
<html lang="en">
<head>
    <title>In Danh sách Sản phẩm</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <style>
        body {margin: 20px}
    </style>
</head>
<body>
<table class="table table-bordered table-condensed table-striped">
    <thead>
    <tr>
        <th>Thứ tự</th>
        <th>Tên nhiệm vụ</th>
        <th>Tên jobs</th>
        <th>Khách hàng</th>
        <th>Level AX</th>
        <th>Số lượng original</th>
        <th>Case path</th>
        <th>Thời gian tạo</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $task)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $task->name }}</td>
            <td>{{ $task->case }}</td>
            <td>{{ $task->customer }}</td>
            <td>{{ $task->level }}</td>
            <td>{{ $task->countRecord }}</td>
            <td>{{ $task->path }}</td>
            <td>{{ formatDate($task->created_at) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
