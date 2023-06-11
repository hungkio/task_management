<div class="list-icons">
    @can('tasks.update')
        <a href="{{ route('admin.tasks.edit', $task->id) }}" class="item-action btn-primary"
           title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
        <a href="{{ route('admin.tasks.clone', $task->id) }}" class="item-action btn-success" title="{{ __('Clone') }}"><i
                class="fal  fa-clone"></i></a>
    @endcan
    @can('tasks.delete')
        <a href="javascript:void(0)" data-url="{{ route('admin.tasks.destroy', $task->id) }}"
           class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
    @endcan
    @if($task->dbcheck == 0)
        <button class="item-action btn-warning dbcheck border-0" onclick="add_dbcheck('{{ route('admin.tasks.double-check', $task->id) }}')"
                title="{{ __('Double Check') }}"><i
                class="fal fa-check-double"></i></button>
    @endif
</div>
<script>
    function add_dbcheck(url) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                if (res.status == true) {
                    showMessage('success', res.message);
                } else {
                    showMessage('error', res.message);
                }
                window.LaravelDataTables['TaskDataTable'].ajax.reload();
            },
        });
    }

</script>
