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
        .task-placeholder {
          background-color: #eee;
          border: 2px dashed #999;
          height: 100px;
          width: 100%;
        }
        .task{
          animation: all 0.5s;
        }
        .in-progress{
          background-color: #1890ff;
        }
        .testing{
          background-color: #FFD700;
        }
        .bug{
          background-color: #BB0000;
        }
        .done{
          background-color: #458B00;
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
      <div class="dashboard d-flex justify-content-between">
        <!-- To do tasks -->
        <div class="taskList task-column shadow-sm mx-2 bg-light p-2">
          <h5>
              In Progress
          </h5>
          <div id="in-progress" class="sortable h-100 d-flex flex-column">
              <!-- card -->
              @foreach ($tasks_editing as $task)
                <div id={{$task->id}}
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-2">{{$task->name}}</div>
                        <div class="status in-progress d-inline p-1 fw-semibold small text-white project-name">
                          In progress
                        </div>
                    </div>
                </div>
              @endforeach
              @foreach ($tasks_rejected as $task)
                <div id={{$task->id}}
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-2">{{$task->name}}</div>
                        <div class="status bug d-inline p-1 fw-semibold small text-white project-name">
                          Bug
                        </div>
                    </div>
                </div>
              @endforeach
          </div>
        </div>
        <!-- To do tasks -->
        <div class="taskList task-column shadow-sm mx-2 bg-light p-2">
            <h5>
                Testing
            </h5>
            <div id="testing" class="sortable d-flex flex-column h-100">
              <!-- card -->
              @foreach ($tasks_testing as $task)
                <div id={{$task->id}}
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-2">{{$task->name}}</div>
                        <div class="status testing d-inline p-1 fw-semibold small text-white project-name">
                          Testing
                        </div>
                    </div>
                </div>
              @endforeach
              
            </div>
        </div>
        <!-- tasks completed -->
        <div class="taskList task-column shadow-sm mx-2 bg-light p-2">
            <h5>
                Done
            </h5>
            <div id="done" class="sortable d-flex flex-column h-100">
                <!-- card -->
                @foreach ($tasks_done as $task)
                  <div id={{$task->id}}
                      class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                      <div class="card-body px-3 py-3">
                          <div class="card-text mb-2">{{$task->name}}</div>
                          <div class="status done d-inline p-1 fw-semibold small text-white project-name">
                            Done
                          </div>
                      </div>
                  </div>
                @endforeach
            </div>
        </div>
      </div>
    </div>
  </div>
</section>

@stop

@push('js')
  <script>
    $(document).ready(function () {
      $('.sortable').sortable({
        connectWith: ".sortable",
        placeholder: "task-placeholder",
        start: function(event, ui) {
          ui.item.toggleClass("dragging");
          taskId = ui.item.attr('id');
        },
        stop: function(event, ui) {
          ui.item.toggleClass("dragging");
        },
        receive: function(event, ui) {
          let thisProcess = $(this).attr('id');
          switch (thisProcess) {
            case 'in-progress':
              processStatus = 4;
              status = 'Bug';
              ui.item.find('.status').css("background-color", "#BB0000")
              break;
            case 'testing':
              status = 'Testing';
              ui.item.find('.status').css("background-color", "#FFD700")
              processStatus = 2;
              break;
            case 'done':
              status = 'Done';
              ui.item.find('.status').css("background-color", "#458B00")
              processStatus = 3;
            default:
              break;
          }
          updateTaskStatus(taskId, processStatus);
          ui.item.find('.status').text(status);
        }
      });
      
      function updateTaskStatus(taskId, processStatus) {
        $.ajax({
          url: "{{ route('admin.tasks.update-status')}}",
          type: 'POST',
          dataType: 'json',
          data: { id: taskId, status: processStatus },
          success: function(response) {
            // Xử lý thành công
            console.log(response);
          },
          error: function(xhr) {
            // Xử lý lỗi
            console.log(xhr.responseText);
          }
        });
      }
    })
  </script>
@endpush
