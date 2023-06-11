<div class="list-icons">
    <a href="{{ route('admin.dbcheck_tasks.edit', $task->id) }}" class="item-action btn-primary"
       title="{{ __('Chỉnh sửa') }}"><i
            class="fal fa-pencil-alt"></i></a>
    <a href="javascript:void(0)" data-url="{{ route('admin.dbcheck_tasks.destroy', $task->id) }}"
       class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
</div>
