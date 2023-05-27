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
      background-color: #ebc334;
    }
    .bug{
      background-color: #BB0000;
    }
    .done{
      background-color: #458B00;
    }
    .content-wrapper{
      overflow: visible;
    }
    .task-column{
      margin: 0 8px;
      display: flex;
      flex-direction: column;
    }
    .task-column:first-child{
      margin-left: 0;
    }
    .task-column:last-child{
      margin-right: 0;
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
        <div class="task-column w-100 shadow-sm bg-white">
          <h5 class="in-progress p-2 text-white">
              In Progress
          </h5>
          <div class="taskList m-2 bg-light flex-grow-1">
            <div id="in-progress" class="sortable h-100 d-flex flex-column">
                <!-- card -->
                <!-- render task todo -->
                @foreach ($tasks_todo as $task)
                  @php
                      $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                      $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                  @endphp
                  <div id={{$task->id}}
                    data-toggle="modal"
                    data-url="{{ route('admin.popup', $task->id) }}"
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow">
                      <div class="card-body px-3 py-3">
                          <div class="card-text mb-1">{{$task->name}}</div>
                          @if (@$editor)
                            <div class="card-text mb-1">
                              <span>Editor: {{$editor->last_name}}</span>
                              -
                              <span class="start-time">Start: {{ $task->start_at && date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
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
                          <div class="d-flex justify-content-between">
                            <div class="status px-2 rounded in-progress d-inline p-1 fw-semibold text-white project-name">
                              To do
                            </div>
                            <button class="start-task px-2 text-white border-0 rounded outline-0 d-inline-block in-progress">Start</button>
                          </div>
                      </div>
                  </div>
                @endforeach
                 <!-- render task editing -->
                @foreach ($tasks_editing as $task)
                  @php
                      $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                      $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                  @endphp
                  <div id={{$task->id}}
                    data-toggle="modal"
                    data-url="{{ route('admin.popup', $task->id) }}"
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow">
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
                          <div class="status px-2 rounded in-progress d-inline p-1 fw-semibold text-white project-name">
                            In progress
                          </div>
                      </div>
                  </div>
                @endforeach
                <!-- render task bug -->
                @foreach ($tasks_rejected as $task)
                  @php
                  $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                  $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                  @endphp
                  <div id={{$task->id}}
                    data-toggle="modal"
                    data-url="{{ route('admin.popup', $task->id) }}"
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow">
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
                          <div class="status rounded bug d-inline p-1 fw-semibold small text-white project-name">
                            Rejected
                          </div>
                      </div>
                  </div>
                @endforeach
            </div>
          </div>
        </div>
        <!-- To do tasks -->
        <div class="task-column w-100 shadow-sm bg-white">
          <h5 class="testing p-2 text-white">
              Testing
          </h5>
          <div class="taskList m-2 bg-light flex-grow-1">
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
                    class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow">
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
                          <div class="status rounded testing d-inline p-1 fw-semibold small text-white project-name">
                            Testing
                          </div>
                      </div>
                  </div>
                @endforeach
  
              </div>
          </div>
        </div>
        <!-- tasks completed -->
        <div class="task-column w-100 shadow-sm bg-white">
          <h5 class="done p-2 text-white">
              Done
          </h5>
          <div class="taskList m-2 bg-light flex-grow-1">
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
                      class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow">
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
                            <div class="status rounded done d-inline p-1 fw-semibold small text-white project-name">
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
            const newTask = "<div id="+response.id+" data-toggle='modal' data-url='admin/popup/"+response.id+"' class='card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow'>"+
                          "<div class='card-body px-3 py-3'>"+
                            "<div class='card-text mb-1'>"+response.name+"</div>"+
                            "<div class='card-text mb-1'>"+
                              "<span>Editor: {{@$editor->last_name}}</span>"+
                              " - "+
                              "<span class='start-time'>Start: </span>"+
                            "</div>"+
                            "<div class='qa-details'></div>"+
                            "<div class='d-flex justify-content-between'>"+
                              "<div class='status px-2 rounded in-progress d-inline p-1 fw-semibold text-white project-name'>"+
                                "To do"+
                              "</div>"+
                              "<button class='start-task px-2 text-white border-0 rounded outline-0 d-inline-block in-progress'>Start</button>"+
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

      function checkStatus(id) {
        status = $('#'+id).find('.status').text();
        return $.trim(status);
      }

      $('.sortable').sortable({
        connectWith: ".sortable",
        placeholder: "task-placeholder",
        start: function(event, ui) {
          ui.item.toggleClass("dragging");
          taskId = ui.item.attr('id');
          let hasSiblings = ui.item.siblings().length > 1;
          let is_inProgress = checkStatus(taskId) == 'In progress';
          ui.item.data('hasSiblings', hasSiblings);
          ui.item.data('is_inProgress', is_inProgress);
        },
        beforeStop: function(event, ui) {
          let hasSiblings = ui.item.data('hasSiblings');
          let is_inProgress = ui.item.data('is_inProgress');
          if(hasSiblings && is_inProgress){
            $('#in-progress').sortable('cancel');
            const message_drop = 'Bạn phải hoàn thành hết các case(s) rejected trước.'
            alert(message_drop);
          }
        },
        stop: function(event, ui) {
          ui.item.toggleClass("dragging");
        },
        receive: function(event, ui) {
          let thisProcess = $(this).attr('id');
          
          switch (thisProcess) {
            case 'in-progress':
              processStatus = 4;
              status = 'Rejected';
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

      function updateTaskStatus(taskId, processStatus, saveStartAt) {
        $.ajax({
          url: "{{ route('admin.update-status')}}",
          type: 'POST',
          dataType: 'json',
          data: { 
            id: taskId, 
            status: processStatus,
            ...(saveStartAt && {confirm: saveStartAt})
          },
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

      //start task
      $(document).on('click', '.start-task', function (event) {
        event.stopPropagation();
        let this_id = $(this).parents('.card').attr('id');
        let saveStartAt = true;
        let currentTime = new Date();
        let formated_time = 'Start: ' + currentTime.getDate() + '/' + (currentTime.getMonth() + 1) + '/' + currentTime.getFullYear() + ' ' + currentTime.getHours() + ':' + currentTime.getMinutes();
        updateTaskStatus(this_id, 1, saveStartAt);
        $(this).siblings('.status').text('In progress');
        $(this).parents().siblings('.card-text').find('.start-time').text(formated_time);
        $(this).remove();
      })

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
