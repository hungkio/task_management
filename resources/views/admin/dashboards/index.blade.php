@extends('admin.layouts.master')

@section('title', __('Sản phẩm'))
@section('page-header')
    <x-page-header>
        {{ Breadcrumbs::render() }}
    </x-page-header>
@stop

@push('css')
    <style>
        .task-column{
            width: 30%
        }
        @media (max-width: 1024px){
            .dashboard{
                width: 1024px;
                overflow-x: auto; 
            }
        }
        @media (max-width: 767.98px) {
            .btn-danger {
                margin-left: 0!important;
            }
        }
        @media (width: 320px) {
            .btn-danger {
                margin-left: .625rem!important;
            }
        }
    </style>
@endpush

@section('page-content')
    
<section class="">
    <div class="position-relative">
        <div class="">
            <div class="dashboard d-flex justify-content-between vh-100">
                <!-- To do tasks -->
                <div class="task-column shadow-sm mx-2 bg-light p-2" id="todo" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <h5>
                        To do
                    </h5>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        <!-- card -->
                        <div id="todotarget1" ondragstart="dragStart(event)" draggable="true"
                            class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                            <div class="card-body px-3 py-3">
                                <div class="card-text mb-2">Details of Task</div>
                                <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
                                    Project Name</div>
                            </div>
                        </div>
                        <!-- card -->
                        <div id="todotarget2" ondragstart="dragStart(event)" draggable="true"
                            class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                            <div class="card-body px-3 py-3">
                                <div class="card-text mb-2">Details of Task</div>
                                <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
                                    Project Name</div>
                            </div>
                        </div>
                        <!-- card -->
                        <div id="todotarget3" ondragstart="dragStart(event)" draggable="true"
                            class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                            <div class="card-body px-3 py-3">
                                <div class="card-text mb-2">Details of Task</div>
                                <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
                                    Project Name</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- To do tasks -->
                <div class="task-column shadow-sm mx-2 bg-light p-2" id="progress" ondrop="drop(event)"
                    ondragover="allowDrop(event)">
                    <h5>
                        Test
                    </h5>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        <!-- card -->
                        <div id="inprogresstarget1" ondragstart="dragStart(event)" draggable="true"
                            class="card rounded-0 w-100 mb-3 border-0 border-start border-warning border-3 shadow-sm">
                            <div class="card-body px-3 py-3">
                                <div class="card-text mb-2">Details of Task</div>
                                <div class="bg-warning d-inline p-1 fw-semibold small text-white project-name">
                                    Project Name</div>
                            </div>
                        </div>
                        <!-- card -->
                        <div id="inprogresstarget2" ondragstart="dragStart(event)" draggable="true"
                            class="card rounded-0 w-100 mb-3 border-0 border-start border-warning border-3 shadow-sm">
                            <div class="card-body px-3 py-3">
                                <div class="card-text mb-2">Details of Task</div>
                                <div class="bg-warning d-inline p-1 fw-semibold small text-white project-name">
                                    Project Name</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- tasks completed -->
                <div class="task-column shadow-sm mx-2 bg-light p-2" id="completed" ondrop="drop(event)"
                    ondragover="allowDrop(event)">
                    <h5>
                        Done
                    </h5>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        <!-- card -->
                        <div id="completedtarget1" ondragstart="dragStart(event)" draggable="true"
                            class="card rounded-0 w-100 mb-3 border-0 border-start border-success border-3 shadow-sm">
                            <div class="card-body px-3 py-3">
                                <div class="card-text mb-2">Details of Task</div>
                                <div class="bg-success d-inline p-1 fw-semibold small text-white project-name">
                                    Project Name</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@stop

@push('js')
    {{$dataTable->scripts()}}
    <script>
        $(document).on('change','#select_status', function () {
            var status = $(this).val();
            var url = $(this).attr('data-url');
            confirmAction('Bạn có muốn thay đổi trạng thái ?', function (result) {
                if (result) {
                    $.ajax({
                        url: url,
                        data: {
                            'status': status
                        },
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if(res.status == true){
                                showMessage('success', res.message);
                            }else{
                                showMessage('error', res.message);
                            }
                            window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                        },
                    });
                }else{
                    window.LaravelDataTables['{{ $dataTable->getTableAttribute('id') }}'].ajax.reload();
                }
            });
        });

        $(document).on('click', '.updateStatus', function () {
            var updateUrl = $(this).data('url');

            confirmAction("Bạn có chắc muốn duyệt mẫu thiết kế này?", function (result) {
                if (result) {
                    $.ajax({
                        type: 'PUT',
                        url: updateUrl,
                        data: {
                            _method: "PUT",
                            status: 2
                        },
                        success: function (res) {
                            if (res.status == 'error') {
                                showMessage('error', res.message);
                            } else {
                                showMessage('success', res.message);
                            }
                            Object.keys(window.LaravelDataTables).forEach(function (table) {
                                window.LaravelDataTables[table].ajax.reload()
                            })
                        }
                    })
                }
            })
        })
        @can('products.create')
        $('.buttons-create').removeClass('d-none')
        @endcan
        @can('products.delete')
        $('.btn-danger').removeClass('d-none')
        @endcan
        @can('products.update')
        $('.btn-warning').removeClass('d-none')
        @endcan

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        //pike
        function dragStart(event) {
            event.dataTransfer.setData("Text", event.target.id);
        }
        function allowDrop(event) {
            event.preventDefault();
        }
        function drop(event) {
            if (event.target.id != "") {
                event.preventDefault();
                const data = event.dataTransfer.getData("Text");
                event.target.appendChild(document.getElementById(data));
                // todo list
                if (event.target.id == "todo") {
                    document.getElementById(data).classList.remove("border-warning", "border-success");
                    document.getElementById(data).classList.add("border-primary");
                    document.getElementById(data).getElementsByClassName("project-name")[0].classList.remove("bg-warning", "bg-success");
                    document.getElementById(data).getElementsByClassName("project-name")[0].classList.add("bg-primary")
                }
                // progress list
                if (event.target.id == "progress") {
                    document.getElementById(data).classList.remove("border-primary", "border-success");
                    document.getElementById(data).classList.add("border-warning");
                    document.getElementById(data).getElementsByClassName("project-name")[0].classList.remove("bg-primary", "bg-success");
                    document.getElementById(data).getElementsByClassName("project-name")[0].classList.add("bg-warning")
                }
                // completed list
                if (event.target.id == "completed") {
                    document.getElementById(data).classList.remove("border-warning", "border-success");
                    document.getElementById(data).classList.add("border-success");
                    document.getElementById(data).getElementsByClassName("project-name")[0].classList.remove("bg-warning", "bg-success");
                    document.getElementById(data).getElementsByClassName("project-name")[0].classList.add("bg-success")
                }
            }
        }
    </script>
@endpush
