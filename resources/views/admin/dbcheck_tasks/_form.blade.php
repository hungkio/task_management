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
                                    :label="__('Tên nhiệm vụ')"
                                    :value="$task->name"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="path"
                                    :placeholder="__('Đường dẫn dropbox')"
                                    :label="__('Đường dẫn dropbox')"
                                    :value="$task->path"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="instruction"
                                    :placeholder="__('Instruction')"
                                    :label="__('Instruction')"
                                    :value="$task->instruction"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="countRecord"
                                    :placeholder="__('Số lượng original')"
                                    :label="__('Số lượng original')"
                                    :value="$task->countRecord"
                                    type="number"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="case"
                                    :placeholder="__('Các case tách phải giống nhau tên job')"
                                    :label="__('Tên job')"
                                    :value="$task->case"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="customer"
                                    :placeholder="__('Mã khách')"
                                    :label="__('Mã khách')"
                                    :value="$task->customer"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        {{ __('Level khách') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="level" class="form-control" data-width="100%" disabled>
                                            <option value=""
                                                    @if(!@$task->level) selected @endif>
                                                Chưa chọn level
                                            </option>
                                            @foreach(\App\Domain\Admin\Models\Admin::LEVEL as $level)
                                                <option value="{{ $level }}"
                                                        @if($level == @$task->level) selected @endif>
                                                    {{ $level }}
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

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        {{ __('Trạng thái') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="status" class="form-control" data-width="100%" disabled>
                                            @foreach(\App\Tasks::STATUS as $key => $status)
                                                <option value="{{ $key }}"
                                                        @if($key == @$task->status) selected @endif>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('status')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <x-text-field
                                    name="editor_check_num"
                                    :placeholder="__('Số ảnh edit')"
                                    :label="__('Số ảnh edit')"
                                    :value="$task->editor_check_num"
                                    type="number"
                                    :disabled="1"
                                >
                                </x-text-field>


                                <x-text-field
                                    name="finish_path"
                                    :placeholder="__('Đường dẫn done')"
                                    :label="__('Đường dẫn done')"
                                    :value="$task->finish_path"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="QA_check_num"
                                    :placeholder="__('Số ảnh done')"
                                    :label="__('Số ảnh done')"
                                    :value="$task->QA_check_num"
                                    type="number"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="QA_note"
                                    :placeholder="__('QA ghi chú')"
                                    :label="__('QA ghi chú')"
                                    :value="$task->QA_note"
                                    :disabled="1"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="dbcheck_num"
                                    :placeholder="__('Số lượng DBC')"
                                    :label="__('Số lượng DBC')"
                                    :value="$task->dbcheck_num"
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
