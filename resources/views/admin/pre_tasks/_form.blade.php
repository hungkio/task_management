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
                                    :placeholder="__('Số lượng original')"
                                    :label="__('Số lượng original')"
                                    :value="$task->countRecord"
                                    type="number"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="case"
                                    :placeholder="__('Các case tách phải giống nhau tên case')"
                                    :label="__('Tên case tách')"
                                    :value="$task->case"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="customer"
                                    :placeholder="__('Mã khách')"
                                    :label="__('Mã khách')"
                                    :value="$task->customer"
                                    required
                                >
                                </x-text-field>

                                <x-text-field
                                    name="share_link"
                                    :placeholder="__('Link Share')"
                                    :label="__('Link Share')"
                                    :value="$task->share_link"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('Level khách') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="level" class="form-control" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->level) selected @endif>
                                                Chưa chọn level
                                            </option>
                                            @foreach($levels as $level)
                                                <option value="{{ $level->name }}"
                                                        @if($level->name == @$task->level) selected @endif>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('level')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.pre_tasks.index') }}" class="btn btn-light">{{ __('Trở lại') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i
                                    class="mi-save mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-type"
                                   data-redirect="{{ route('admin.pre_tasks.index') }}">{{ __('Lưu & Thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-type"
                                   data-redirect="{{ route('admin.pre_tasks.create') }}">{{ __('Lưu & Thêm mới') }}</a>
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
