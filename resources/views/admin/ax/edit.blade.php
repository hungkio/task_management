@extends('admin.layouts.master')

@section('title', __('Chỉnh sửa :model', ['model' => $ax->display_name]))
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
    <form action="{{ route('admin.ax.update', $ax) }}" method="POST" data-block>
        @csrf
        @method('PUT')
        <div class="d-flex align-items-start flex-column flex-md-row">

            <!-- Left content -->
            <div class="w-100 order-2 order-md-1 left-content">
                <div class="row">
                    <div class="col-md-12">
                        <x-card>
                            <div class="collapse show" id="general">
                                <x-text-field
                                    name="name"
                                    :placeholder="__('SE, INV, ...')"
                                    :label="__('Tên')"
                                    :value="$ax->name"
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
                                        <input autocomplete="new-password" type="number" name="cost[]" id="cost" class="form-control" placeholder="Editor cost" value="{{ $cost[0] ?? 0 }}">

                                    </div>
                                    <div class="col-lg-3">
                                        <input autocomplete="new-password" type="number" name="cost[]" id="cost" class="form-control" placeholder="QA cost" value="{{ $cost[1] ?? 0 }}">
                                    </div>
                                    <div class="col-lg-3">
                                        <input autocomplete="new-password" type="number" name="cost[]" id="cost" class="form-control" placeholder="CTV cost" value="{{ $cost[2] ?? 0 }}">
                                        @error('cost')
                                        <span class="form-text text-danger">
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
                                    :value="$ax->priority"
                                    required
                                >
                                </x-text-field>
                            </div>

                            <div class="collapse show" >
                                <x-text-field
                                    name="estimate_editor"
                                    :label="__('Estimate Editor')"
                                    type="number"
                                    :value="$ax->estimate_editor"
                                    required
                                >
                                </x-text-field>
                            </div>

                            <div class="collapse show" >
                                <x-text-field
                                    name="estimate_QA"
                                    :label="__('Estimate QA')"
                                    :value="$ax->estimate_QA"
                                    type="number"
                                    required
                                >
                                </x-text-field>
                            </div>                            <div class="d-flex justify-content-center align-items-center action" id="action-form">
                                <a href="{{ route('admin.ax.index') }}" class="btn btn-light"><i class="fal fa-arrow-left mr-2"></i>{{ __('Trở về') }}</a>
                                <div class="btn-group ml-3">
                                    <button class="btn btn-primary btn-block" data-loading><i class="fal fa-check mr-2"></i>{{ __('Lưu') }}</button>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"></button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.ax.index') }}">{{ __('Lưu và thoát') }}</a>
                                        <a href="javascript:void(0)" class="dropdown-item submit-type" data-redirect="{{ route('admin.ax.create') }}">{{ __('Lưu và tạo mới') }}</a>
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
