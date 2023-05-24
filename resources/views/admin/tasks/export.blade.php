<table>
    <thead>
    <tr>
        <th>Thứ tự</th>
        <th>Tên case</th>
        <th>Tên case tách</th>
        <th>Khách hàng</th>
        <th>Level</th>
        <th>Trạng thái</th>
        <th>Editor</th>
        <th>QA</th>
        <th>Số lượng original</th>
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
            <td>{{ \App\Tasks::STATUS[$task->status] }}</td>
            <td>{{ $task->editor->fullName ?? '' }}</td>
            <td>{{ $task->QA->fullName ?? '' }}</td>
            <td>{{ $task->countRecord }}</td>
            <td>{{ formatDate($task->created_at) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
