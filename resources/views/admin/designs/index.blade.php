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
                        @foreach ($tasks as $task)
                            
                            <div id="todotarget1" ondragstart="dragStart(event)" draggable="true"
                                class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                                <div class="card-body px-3 py-3">
                                    <div class="card-text mb-2">{{$task->name}}</div>
                                    <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
                                        Project Name</div>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                </div>
                <!-- To do tasks -->
                <div class="task-column shadow-sm mx-2 bg-light p-2" id="progress" ondrop="drop(event)"
                    ondragover="allowDrop(event)">
                    <h5>
                        Test
                    </h5>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        
                    </div>
                </div>
                <!-- tasks completed -->
                <div class="task-column shadow-sm mx-2 bg-light p-2" id="completed" ondrop="drop(event)"
                    ondragover="allowDrop(event)">
                    <h5>
                        Done
                    </h5>
                    <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                        
                    </div>
                </div>
            </div>
        </div>
</section>

@stop

@push('js')
    <script>
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
