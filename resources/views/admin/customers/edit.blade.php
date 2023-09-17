@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $customer->display_name]))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@push('css')
    <style>
        @media (max-width: 767.98px) {
            .btn {
                width: auto!important;
            }
        }
    </style>
@endpush

@push('js')
    <script !src="">
        $(function () {
            $('.form-check-input-styled').uniform();
            $('.permission-group-actions > .allow-all, .permission-group-actions > .deny-all').on('click', (e) => {
                let action = e.currentTarget.className.split(/\s+/)[2].split(/-/)[0];
                $(e.currentTarget).closest('.permission-group')
                    .find(`.permission-${action}`)
                    .each((index, value) => {
                        $(value).prop('checked', true);
                    });
                $.uniform.update();
            });
            $('.permission-parent-actions > .allow-all, .permission-parent-actions > .deny-all').on('click', (e) => {
                let action = e.currentTarget.className.split(/\s+/)[2].split(/-/)[0];
                $(`.permission-${action}`).prop('checked', true);
                $.uniform.update();
            });
        })
    </script>
@endpush

@section('page-content')
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" data-block>
        @csrf
        @method('PUT')
        <div class="d-flex align-items-start flex-column flex-md-row">

            <!-- Left content -->
            <div class="w-100 order-2 order-md-1 left-content">
                <div class="row">
                    <div class="col-md-12">
                        <x-card>
                            <fieldset>
                                <legend class="font-weight-semibold text-uppercase font-size-sm">
                                    {{ __('Chung') }}
                                </legend>
                                <div class="collapse show" id="general">
                                    <x-text-field
                                        name="name"
                                        :placeholder="__('Admin, Member')"
                                        :label="__('Tên')"
                                        :value="$customer->name"
                                    >
                                    </x-text-field>
                                </div>

                                <div class="form-group row">
                                    <label for="select-taxon" class="col-lg-2 text-lg-right col-form-label">
                                        <span class="text-danger">*</span> {{ __('Level AX') }}
                                    </label>
                                    <div class="col-lg-9">
                                        <select name="ax" class="form-control select2" data-width="100%">
                                            <option value="">Chưa chọn level AX</option>
                                            @foreach($AX as $level)
                                                <option value="{{ $level->name }}" @if($customer->ax == $level->name) selected @endif>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="clearfix"></div>
                                        @error('ax')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </fieldset>

                            <div class="d-flex justify-content-center align-items-center action" id="action-form">
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-light"><i class="fal fa-arrow-left mr-2"></i>{{ __('Trở về') }}</a>
                                <div class="btn-group ml-3">
                                    <button class="btn btn-primary btn-block" data-loading><i class="fal fa-check mr-2"></i>{{ __('Lưu') }}</button>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.customers.index') }}">{{ __('Lưu và thoát') }}</a>
                                        <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.customers.create') }}">{{ __('Lưu và tạo mới') }}</a>
                                    </div>
                                </div>
                            </div>
                        </x-card>
                    </div>
                </div>

            </div>
            <!-- /left content -->
        </div>
    </form>
@stop
@push('js')
@endpush
