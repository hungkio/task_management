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
                                </x-text-field>

                                <x-textarea-field
                                    name="instruction"
                                    :placeholder="__('Instruction')"
                                    :label="__('Instruction')"
                                    :value="$task->instruction"
                                    class="wysiwyg"
                                >
                                </x-textarea-field>

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
                                    :placeholder="__('Các case tách phải giống nhau tên job')"
                                    :label="__('Tên job')"
                                    :value="$task->case"
                                    required
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('Mã khách') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="customer" class="form-control select2" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->customer) selected @endif>
                                                Chưa chọn khách
                                            </option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->name }}"
                                                        @if($customer->name == @$task->customer) selected @endif>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('customer')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('AX') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="level" class="form-control select2" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->level) selected @endif>
                                                Chưa chọn AX
                                            </option>
                                            @foreach($AX as $ax)
                                                <option value="{{ $ax->name }}"
                                                        @if($ax->name == @$task->level) selected @endif>
                                                    {{ $ax->name }}
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
                                        <span class="text-danger">*</span> {{ __('Trạng thái') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="status" class="form-control select2" data-width="100%">
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
                                        {{ __('Editor') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="editor_id" class="form-control select2" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->editor_id) selected @endif>
                                                Chưa assign
                                            </option>
                                            @foreach($editors as $editor)
                                                <option value="{{ $editor->id }}"
                                                        @if($editor->id == @$task->editor_id) selected @endif>
                                                    {{ $editor->email }}
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

                                <x-text-field
                                    name="editor_check_num"
                                    :placeholder="__('Số ảnh edit')"
                                    :label="__('Số ảnh edit')"
                                    :value="$task->editor_check_num"
                                    type="number"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        {{ __('QA') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="QA_id" class="form-control select2" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->QA_id) selected @endif>
                                                Chưa assign
                                            </option>
                                            @foreach($QAs as $qa)
                                                <option value="{{ $qa->id }}"
                                                        @if($qa->id == @$task->QA_id) selected @endif>
                                                    {{ $qa->email }}
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

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        {{ __('DBC') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="dbcheck" class="form-control select2" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->dbcheck) selected @endif>
                                                Chọn người DBC
                                            </option>
                                            @foreach($dbcs as $dbc)
                                                <option value="{{ $dbc->id }}"
                                                        @if($dbc->id == $task->dbcheck) selected @endif>
                                                    {{ $dbc->email}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('dbcheck')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <x-text-field
                                    name="finish_path"
                                    :placeholder="__('Đường dẫn done')"
                                    :label="__('Đường dẫn done')"
                                    :value="$task->finish_path"
                                >
                                </x-text-field>

                                <x-text-field
                                    name="QA_check_num"
                                    :placeholder="__('Số ảnh done')"
                                    :label="__('Số ảnh done')"
                                    :value="$task->QA_check_num"
                                    type="number"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right" for="redo">
                                        Redo:
                                    </label>
                                    <div class="col-lg-9">
                                        <input autocomplete="new-password" type="checkbox" name="redo" id="redo"
                                               @if($task->redo) checked @endif>
                                    </div>
                                </div>

                                <x-text-field
                                    name="QA_note"
                                    :placeholder="__('QA ghi chú')"
                                    :label="__('QA ghi chú')"
                                    :value="$task->QA_note"
                                >
                                </x-text-field>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        {{ __('Bad') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="redo_note" class="form-control" data-width="100%">
                                            <option value=""
                                                    @if(!@$task->redo_note) selected @endif>
                                                Lựa chọn
                                            </option>
                                            @foreach(\App\Domain\Admin\Models\Admin::BAD as $bad)
                                                <option value="{{ $bad }}"
                                                        @if($bad == @$task->redo_note) selected @endif>
                                                    {{ $bad }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('redo_note')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        {{ __('Excellent') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="excellent" class="form-control" data-width="100%">
                                            <option value="{{ \App\Tasks::EXCELLENT }}"
                                                    @if(\App\Tasks::EXCELLENT == @$task->excellent) selected @endif>
                                                {{ 'EXCELLENT' }}
                                            </option>
                                            <option value="{{ \App\Tasks::NOT_EXCELLENT }}"
                                                    @if(\App\Tasks::NOT_EXCELLENT == @$task->excellent) selected @endif>
                                                {{ 'NOT EXCELLENT' }}
                                            </option>
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('excellent')
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
