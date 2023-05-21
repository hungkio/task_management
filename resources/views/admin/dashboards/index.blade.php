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
          height: 150px;
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
<div id="popupContainer"></div>
<section class="dashboard-case">
  <div class="position-relative">
    <div class="">
      <div class="dashboard d-flex justify-content-between">
        <!-- To do tasks -->
        <div class="taskList w-100 task-column shadow-sm mx-2 bg-light p-2">
          <h5>
              In Progress
          </h5>
          <div id="in-progress" class="sortable h-100 d-flex flex-column">
              <!-- card -->
              @foreach ($tasks_editing as $task)
                @php
                    $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                    $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                @endphp
                <div id={{$task->id}}
                  data-toggle="modal"
                  data-url="{{ route('admin.popup', $task->id) }}"
                  class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-1">{{$task->name}}</div>
                        @if (@$editor)
                          <div class="card-text mb-1">
                            <span>Editor: {{$editor->last_name}}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                          </div>
                        @endif
                        <div class="qa-details">
                          @if (@$qa)
                            <div class="card-text mb-1">
                              <span>QA: {{ $qa->last_name }}</span>
                              -
                              <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                            </div>
                            <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                          @endif
                        </div>
                        <div class="status in-progress d-inline p-1 fw-semibold small text-white project-name">
                          In progress
                        </div>
                    </div>
                </div>
              @endforeach
              @foreach ($tasks_rejected as $task)
                @php
                $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                @endphp
                <div id={{$task->id}}
                  data-toggle="modal"
                  data-url="{{ route('admin.popup', $task->id) }}"
                  class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-1">{{$task->name}}</div>
                        @if (@$editor)
                          <div class="card-text mb-1">
                            <span>Editor: {{$editor->last_name}}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                          </div>
                        @endif
                        <div class="qa-details">
                          @if (@$qa)
                            <div class="card-text mb-1">
                              <span>QA: {{ $qa->last_name }}</span>
                              -
                              <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                            </div>
                            <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                          @endif
                        </div>
                        <div class="status bug d-inline p-1 fw-semibold small text-white project-name">
                          Bug
                        </div>
                    </div>
                </div>
              @endforeach
          </div>
        </div>
        <!-- To do tasks -->
        <div class="taskList w-100 task-column shadow-sm mx-2 bg-light p-2">
            <h5>
                Testing
            </h5>
            <div id="testing" class="sortable d-flex flex-column h-100">
              <!-- card -->
              @foreach ($tasks_testing as $task)
                @php
                  $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                  $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                @endphp
                <div id={{$task->id}}
                  data-toggle="modal"
                  data-url="{{ route('admin.popup', $task->id) }}"
                  class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                    <div class="card-body px-3 py-3">
                        <div class="card-text mb-1">{{$task->name}}</div>
                        @if (@$editor)
                          <div class="card-text mb-1">
                            <span>Editor: {{$editor->last_name}}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                          </div>
                        @endif
                        <div class="qa-details">
                          @if (@$qa)
                            <div class="card-text mb-1">
                              <span>QA: {{ $qa->last_name }}</span>
                              -
                              <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                            </div>
                            <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                          @endif
                        </div>
                        <div class="status testing d-inline p-1 fw-semibold small text-white project-name">
                          Testing
                        </div>
                    </div>
                </div>
              @endforeach

            </div>
        </div>
        <!-- tasks completed -->
        <div class="taskList w-100 task-column shadow-sm mx-2 bg-light p-2">
            <h5>
                Done
            </h5>
            <div id="done" class="sortable d-flex flex-column h-100">
                <!-- card -->
                @foreach ($tasks_done as $task)
                  @php
                    $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                    $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                  @endphp
                  <div id={{$task->id}}
                    data-toggle="modal"
                    data-url="{{ route('admin.popup', $task->id) }}"
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm">
                      <div class="card-body px-3 py-3">
                          <div class="card-text mb-1">{{$task->name}}</div>
                          @if (@$editor)
                            <div class="card-text mb-1">
                              <span>Editor: {{$editor->last_name}}</span>
                              -
                              <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                            </div>
                          @endif
                          <div class="qa-details">
                            @if (@$qa)
                              <div class="card-text mb-1">
                                <span>QA: {{ $qa->last_name }}</span>
                                -
                              <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                              </div>
                              <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                            @endif
                          </div>
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
      function addNewTask() {
        $.ajax({
          url: "{{ route('admin.assign-editor')}}",
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            const startAt = new Date(response.start_at);
            const formattedStartAt = `${startAt.getDate()}/${startAt.getMonth() + 1}/${startAt.getFullYear()} ${startAt.getHours()}:${startAt.getMinutes()}`;
            const newTask = "<div id="+response.id+" data-toggle='modal' data-url='admin/popup/"+response.id+"' class='card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow-sm'>"+
                          "<div class='card-body px-3 py-3'>"+
                            "<div class='card-text mb-1'>"+response.name+"</div>"+
                            "<div class='card-text mb-1'>"+
                              "<span>Editor: {{@$editor->last_name}}</span>"+
                              " - "+
                              "<span>Start: "+formattedStartAt+"</span>"+
                            "</div>"+
                            "<div class='status in-progress d-inline p-1 fw-semibold small text-white project-name'>"+
                              "In progress"+
                            "</div>"+
                          "</div>"+
                        "</div>"
              if (response.name) {
                $('#in-progress').append(newTask);
              }
          },
          error: function(xhr) {
            console.log(xhr.responseText);
          }
        });
      }

      function assignQA(taskId) {
        $.ajax({
          url: "admin/assign-qa/"+taskId,
          type: "GET",
          dataType: 'json',
          success: function(response) {
            const qaStart = new Date(response.task.QA_start);
            const formattedQaStart = `${qaStart.getDate()}/${qaStart.getMonth() + 1}/${qaStart.getFullYear()} ${qaStart.getHours()}:${qaStart.getMinutes()}`;
            const qaDetails = "<div class='card-text mb-1'>"+
                                "<span>QA: "+response.QA.last_name+"</span>"+
                                " - "+
                                "<span>Start: "+formattedQaStart+"</span>"+
                              "</div>"+
                              "<div class='card-text mb-1'>QA checked: "+(response.task.QA_check_num ? response.task.QA_check_num : '')+"</div>"
            $('#'+taskId).find('.qa-details').append(qaDetails);
          },
          error: function(xhr) {
            console.log(xhr.responseText);
          }
        })
      }

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

          if (processStatus == 2) {
            addNewTask();

            //chỉ trường hợp task in-progress mới append html qaDetails
            if (ui.item.has(".in-progress").length && ui.item.find(".qa-details").find('div').length == 0) {
              assignQA(taskId);
            }
          }
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

      // when click on task, show popup
      $(document).on('click', '.dashboard-case .card', function() {
        var popupUrl = $(this).data('url');

        $.ajax({
          type: 'Get',
          url: popupUrl,
          success: function(res) {
            if (res.status == 'error') {
              showMessage('error', res.message)
            } else {
              $('#popupContainer').html(res)
              $('#popup').modal('show');
            }
          }
        })
      })
    })
  </script>
@endpush
