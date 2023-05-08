<table>
    <thead>
        <tr>
            <th>Tên</th>
            <th>Chức vụ</th>
            <th>Số ĐT</th>
            <th>Thời gian tạo</th>
        </tr>
    </thead>
    <tbody>
    @foreach($data as $staff)
        <tr>
            <td>{{ $staff->name }}</td>
            <td>{{ \App\Staffs::ROLE[$staff->role] }}</td>
            <td>{{ $staff->phone }}</td>
            <td>{{ formatDate($staff->created_at) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
