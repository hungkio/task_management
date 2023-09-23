@extends('admin.layouts.master')

@section('title', __('Tạo AX'))
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
    <form action="{{ route('admin.ax.store') }}" method="POST" data-block>
        @csrf
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
                                    :placeholder="__('SE, INV, ...')"
                                    :label="__('Tên')"
                                    required
                                >
                                </x-text-field>
                            </div>

                            <div class="collapse show" >
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label text-lg-right" for="cost">
                                        <span class="text-danger">*</span>
                                        Cost:
                                    </label>
                                    <div class="col-lg-3">
                                        <input autocomplete="new-password" type="number" name="cost[]" id="cost" class="form-control" placeholder="Editor cost" value="0">

                                    </div>
                                    <div class="col-lg-3">
                                        <input autocomplete="new-password" type="number" name="cost[]" id="cost" class="form-control" placeholder="QA cost" value="0">
                                    </div>
                                    <div class="col-lg-3">
                                        <input autocomplete="new-password" type="number" name="cost[]" id="cost" class="form-control" placeholder="CTV cost" value="0">
                                        @error('cost')
                                        <span class="form-text text-danger">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="collapse show" >
                                <x-text-field
                                    name="priority"
                                    :label="__('Priority')"
                                    type="number"
                                    required
                                >
                                </x-text-field>
                            </div>

                            <div class="collapse show" >
                                <x-text-field
                                    name="estimate_editor"
                                    :label="__('Estimate Editor')"
                                    required
                                >
                                </x-text-field>
                            </div>

                            <div class="collapse show" >
                                <x-text-field
                                    name="estimate_QA"
                                    :label="__('Estimate QA')"
                                    required
                                >
                                </x-text-field>
                            </div>
                            <div class="collapse show" >
                                <x-text-field
                                    name="real_amount"
                                    :label="__('Tỷ lệ done input')"
                                    :placeholder="0.25"
                                    type="number"
                                >
                                </x-text-field>
                            </div>
                        </fieldset>

                    </x-card>
                    <div class="d-flex justify-content-center align-items-center action" id="action-form">
                        <a href="{{ route('admin.ax.index') }}" class="btn btn-light"><i class="fal fa-arrow-left mr-2"></i>{{ __('Trở lại') }}</a>
                        <div class="btn-group ml-3">
                            <button class="btn btn-primary btn-block" data-loading><i class="fal fa-check mr-2"></i>{{ __('Lưu') }}</button>
                            <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.ax.index') }}">{{ __('Lưu và thoát') }}</a>
                                <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.ax.create') }}">{{ __('Lưu và tạo mới') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /left content -->
    </div>
    </form>
@stop

@push('js')
@endpush
