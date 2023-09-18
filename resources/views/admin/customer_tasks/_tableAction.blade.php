<div class="list-icons">
    @can('customer.view')
        <a href="{{ route('admin.customer_tasks.edit', $task->id) }}" class="item-action btn-primary" title="{{ __('Edit') }}"><i
                class="fal fa-pencil-alt"></i></a>
    @endcan
</div>
