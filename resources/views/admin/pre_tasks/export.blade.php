<table>
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
