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

                            <div class="collapse show" id="general">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right" for="name">
                                        Job name:
                                    </label>
                                    <div class="col-lg-9">
                                        <input autocomplete="new-password" readonly type="text" name="name" id="name" class="form-control is-valid" placeholder="Tên case" value="{{ $task->case }}" aria-describedby="name-error" aria-invalid="false"><span id="name-error" class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="collapse show" id="general">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right" for="name">
                                        Note:
                                    </label>
                                    <div class="col-lg-9">
                                        <textarea rows="10" autocomplete="new-password" type="text" name="customer_note" id="customer_note" class="form-control is-valid"
                                                  placeholder="Customer note">{!! $task->customer_note !!}</textarea>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action-div" id="action-form">
                        <a href="{{ route('admin.customer_tasks.index') }}" class="btn btn-light">{{ __('Back') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i
                                    class="mi-save mr-2"></i>{{ __('Save') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
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
