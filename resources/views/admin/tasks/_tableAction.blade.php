<div class="list-icons">
    @can('tasks.update')
        <a href="{{ route('admin.tasks.edit', $id) }}" class="item-action btn-primary" title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
        <a href="{{ route('admin.tasks.clone', $id) }}" class="item-action btn-success" title="{{ __('Clone') }}"><i
                class="fal  fa-clone" ></i></a>
    @endcan
    @can('tasks.delete')
        <a href="javascript:void(0)" data-url="{{ route('admin.tasks.destroy', $id) }}"
           class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
    @endcan

</div>
