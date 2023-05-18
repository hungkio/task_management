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
        <div class="taskList task-column shadow-sm mx-2 bg-light p-2" id="todo">
          <h5>
              To do
          </h5>
          <div class="d-flex flex-column justify-content-center mt-3 ">
              <!-- card -->
              @foreach ($tasks_editing as $task)
                  
                  <div id={{$task->id}} class="task"
                      class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                      <div class="card-body px-3 py-3">
                          <div class="card-text mb-2">{{$task->name}}</div>
                          <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
                            In progress
                          </div>
                      </div>
                  </div>
              @endforeach
              
          </div>
        </div>
        <!-- To do tasks -->
        <div class="taskList task-column shadow-sm mx-2 bg-light p-2" id="progress">
            <h5>
                Test
            </h5>
            <div class="d-flex flex-column justify-content-center mt-3">
              <!-- card -->
              @foreach ($tasks_testing as $task)
                <div id={{$task->id}} class="task"
                    class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-2">{{$task->name}}</div>
                        <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
                          Testing
                        </div>
                    </div>
                </div>
              @endforeach
            </div>
        </div>
        <!-- tasks completed -->
        <div class="taskList task-column shadow-sm mx-2 bg-light p-2" id="completed">
            <h5>
                Done
            </h5>
            <div class="d-flex flex-column justify-content-center mt-3">
                <!-- card -->
                @foreach ($tasks_done as $task)
                  <div id={{$task->id}} class="task"
                      class="card rounded-0 w-100 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                      <div class="card-body px-3 py-3">
                          <div class="card-text mb-2">{{$task->name}}</div>
                          <div class="bg-primary d-inline p-1 fw-semibold small text-white project-name">
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

      // Thiết lập các phần tử có thể kéo và thả (draggable)
      $('.task').draggable({
        revert: 'invalid', // Trả về vị trí ban đầu nếu không thả vào đúng phần tử
        cursor: 'move',
        start: function() {
          var taskId = $(this).attr('id');
          $(this).data('taskId', taskId);
        }
      });
      // Thiết lập phần tử chấp nhận thả (droppable)
      $('.taskList').droppable({
        accept: '.task', // Chỉ chấp nhận các phần tử có lớp 'task'
        drop: function() {
          let thisProcess = $(this).attr('id');
          switch (thisProcess) {
            case 'todo':
              processStatus = 1;
              break;
            case 'progress':
              processStatus = 2;
              break;
            case 'completed':
              processStatus = 3;
            default:
              break;
          }
          let taskId = $('.task.ui-draggable-dragging').data('taskId');
          updateTaskStatus(taskId, processStatus);
        }
      });
      
      function updateTaskStatus(taskId, processStatus) {
        $.ajax({
          url: 'admin/tasks/' + taskId,
          type: 'PUT',
          dataType: 'json',
          data: { status: processStatus },
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
