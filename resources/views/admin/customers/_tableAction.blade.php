<div class="list-icons">
    @can('customers.update')
        <a href="{{ route('admin.customers.edit', $id) }}" class="item-action btn-primary" title="{{ __('Chỉnh sửa') }}"><i
                class="fal fa-pencil-alt"></i></a>
    @endcan
    @can('customers.delete')
        <a href="javascript:void(0)" data-url="{{ route('admin.customers.destroy', $id) }}"
           class="item-action js-delete btn-danger" title="{{ __('Xóa') }}"><i class="fal fa-trash-alt"></i></a>
    @endcan
</div>
