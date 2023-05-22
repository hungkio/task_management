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

                                <x-text-field
                                    name="finish_path"
                                    :placeholder="__('Đường dẫn done')"
                                    :label="__('Đường dẫn done')"
                                    :value="$task->finish_path"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="QA_check_num"
                                    :placeholder="__('Số ảnh đã kiểm tra')"
                                    :label="__('Số ảnh đã kiểm tra')"
                                    :value="$task->QA_check_num"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="QA_note"
                                    :placeholder="__('QA ghi chú')"
                                    :label="__('QA ghi chú')"
                                    :value="$task->QA_note"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right" for="redo">
                                        Redo:
                                    </label>
                                    <div class="col-lg-9">
                                        <input autocomplete="new-password" type="checkbox" name="redo" id="redo" placeholder="Đường dẫn done" @if($task->redo) checked @endif>
                                    </div>
                                </div>

                                <x-text-field
                                    name="redo_note"
                                    :placeholder="__('Ghi chú redo')"
                                    :label="__('Ghi chú redo')"
                                    :value="$task->redo_note"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('Trạng thái') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="status" class="form-control" data-width="100%">
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

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('Editor') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="editor_id" class="form-control" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->editor_id) selected @endif>
                                                Chưa assign
                                            </option>
                                            @foreach($editors as $editor)
                                                <option value="{{ $editor->id }}"
                                                        @if($editor->id == @$task->editor_id) selected @endif>
                                                    {{ $editor->fullName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('editor_id')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('QA') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="QA_id" class="form-control" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->QA_id) selected @endif>
                                                Chưa assign
                                            </option>
                                            @foreach($QAs as $qa)
                                                <option value="{{ $qa->id }}"
                                                        @if($qa->id == @$task->QA_id) selected @endif>
                                                    {{ $qa->fullName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('QA_id')
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
