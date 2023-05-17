<form action="{{ $url }}" method="POST" data-block enctype="multipart/form-data" id="post-form">
    @csrf
    @method($method ?? 'POST')

    <div class="d-flex align-items-start flex-column flex-md-row">

        <!-- Left content -->
        <div class="w-100 order-2 order-md-1 left-content">
            <div class="row">
                <div class="col-md-12">
                    <x-card>
                        <fieldset>
                            <legend class="font-weight-semibold text-uppercase font-size-sm">
                                {{ __('Chung') }}
                                @if($task->id)
                                    | <a href="" class="text-primary font-weight-semibold"
                                         target="_blank">{{ Str::limit($task->name, 20) }}</a>
                                @endif
                            </legend>
                            <div class="collapse show" id="general">
                                <x-text-field
                                    name="name"
                                    :placeholder="__('Tên case')"
                                    :label="__('Tên case')"
                                    :value="$task->name"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="path"
                                    :placeholder="__('Đường dẫn dropbox')"
                                    :label="__('Đường dẫn dropbox')"
                                    :value="$task->path"
                                    required
                                >
                                    {!! $task->phone ?? null !!}
                                </x-text-field>

                                <x-text-field
                                    name="countRecord"
                                    :placeholder="__('Số lượng ảnh')"
                                    :label="__('Số lượng ảnh')"
                                    :value="$task->countRecord"
                                    type="number"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="date"
                                    :placeholder="__('31 12')"
                                    :label="__('Ngày tháng')"
                                    :value="$task->date"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="month"
                                    :placeholder="__('January, February, ...')"
                                    :label="__('Tháng')"
                                    :value="$task->month"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="case"
                                    :placeholder="__('Tên thư mục case')"
                                    :label="__('Tên thư mục case')"
                                    :value="$task->case"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="customer"
                                    :placeholder="__('Tên thư mục khách hàng')"
                                    :label="__('Tên thư mục khách hàng')"
                                    :value="$task->customer"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="estimate"
                                    :placeholder="__('Số tiếng làm dự kiến')"
                                    :label="__('Thời gian làm dự kiến')"
                                    :value="$task->estimate"
                                    type="number"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="level"
                                    :placeholder="__('Level độ khó case')"
                                    :label="__('Level case')"
                                    :value="$task->level"
                                    type="number"
                                >
                                </x-text-field>
                            </div>

                        </fieldset>

                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.tasks.index') }}" class="btn btn-light">{{ __('Trở lại') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i
                                    class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-type"
                                   data-redirect="{{ route('admin.tasks.index') }}">{{ __('Lưu & Thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-type"
                                   data-redirect="{{ route('admin.tasks.create') }}">{{ __('Lưu & Thêm mới') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /left content -->
    </div>
</form>
@push('js')
    <script>

    </script>
@endpush()
