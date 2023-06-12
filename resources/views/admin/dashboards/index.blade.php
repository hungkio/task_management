@extends('admin.layouts.master')

@section('title', __('Sản phẩm'))
@section('page-header')
  <x-page-header>
    {{ Breadcrumbs::render() }}
  </x-page-header>
@stop

@push('css')
  <style>
    .task-column {
      width: 30%
    }

    .task-placeholder {
      background-color: #eee;
      border: 2px dashed #999;
      height: 150px;
      width: 100%;
    }

    .task {
      animation: all 0.5s;
    }

    .in-progress {
      background-color: #1890ff;
    }

    .testing {
      background-color: #ebc334;
    }

    .bug {
      background-color: #BB0000;
    }

    .done {
      background-color: #458B00;
    }

    .content-wrapper {
      overflow: visible;
    }

    .task-column {
      margin: 0 8px;
      display: flex;
      flex-direction: column;
    }

    .task-column:first-child {
      margin-left: 0;
    }

    .task-column:last-child {
      margin-right: 0;
    }
    .start-task:hover{
      background-color: #0069d9;
    }
    .done-task:hover{
      background-color: #3a7301;
    }
    @media (max-width: 1024px) {
      .dashboard {
        width: 1024px;
        overflow-x: auto;
      }
    }

    @media (max-width: 767.98px) {
      .btn-danger {
        margin-left: 0 !important;
      }
    }

    @media (width: 320px) {
      .btn-danger {
        margin-left: .625rem !important;
      }
    }
  </style>
@endpush

@section('page-content')
<div id="popupContainer"></div>

<section class="dashboard-case">
  <div class="d-flex mb-4 p-2 bg-white shadow-sm">
    <form method="GET" action="{{route('admin.dashboards')}}">
      @csrf
      <label class="ml-2" for="filter-by-user">Filter:</label>
      <input type="text" name="filter-by-user" placeholder="by email or customer" value="{{$input_filter}}">
      <button class="outline-0 border shadow-sm" type="submit">Submit</button>
    </form>
  </div>
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
                       class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow"
                       editor-id="{{ $task->editor_id }}"
                       qa-id="{{ $task->QA_id }}"
                       data-editor-check-num="{{ $task->editor_check_num }}"
                       data-finish-path="{{ $task->finish_path }}">
                    <div class="card-body px-3 py-3">
                      <div class="card-text mb-1">{{$task->name}}</div>
                      @if (@$editor)
                        <div class="card-text mb-1">
                          <span>Editor: {{$editor->email}}</span>
                          -
                          <span
                            class="start-time">Start: {{ $task->start_at && date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                        </div>
                      @endif
                      <div class="qa-details">
                        @if (@$qa)
                          <div class="card-text mb-1">
                            <span>QA: {{ $qa->email }}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                          </div>
                          <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                        @endif
                      </div>
                      <div class="button-box d-flex justify-content-between">
                        <div
                          class="status px-2 rounded in-progress d-inline-block p-1 fw-semibold text-white project-name">
                          To do
                        </div>
                        @if ($task->instruction)
                          <div class="px-2 rounded btn-warning d-inline-block p-1 fw-semibold text-white project-name">
                            Instruction
                          </div>
                        @endif
                        <button
                          class="start-task px-2 text-white border-0 rounded outline-0 d-inline-block in-progress">Start
                        </button>
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
                       class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow"
                       editor-id="{{ $task->editor_id }}"
                       data-editor-check-num="{{ $task->editor_check_num }}"
                       data-finish-path="{{ $task->finish_path }}"
                       qa-id="{{ $task->QA_id }}">
                    <div class="card-body px-3 py-3">
                      <div class="card-text mb-1">{{$task->name}}</div>
                      @if (@$editor)
                        <div class="card-text mb-1">
                          <span>Editor: {{$editor->email}}</span>
                          -
                          <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                        </div>
                      @endif
                      <div class="qa-details">
                        @if (@$qa)
                          <div class="card-text mb-1">
                            <span>QA: {{ $qa->email }}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                          </div>
                          <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                        @endif
                      </div>
                      <div class="button-box d-flex justify-content-between">
                        <div
                          class="status px-2 rounded in-progress d-inline-block p-1 fw-semibold text-white project-name">
                          In progress
                        </div>
                        @if ($task->instruction)
                          <div
                            class=" px-2 rounded btn-warning d-inline-block p-1 fw-semibold text-white project-name">
                            Instruction
                          </div>
                        @endif
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
                       class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow"
                       editor-id="{{ $task->editor_id }}"
                       data-editor-check-num="{{ $task->editor_check_num }}"
                       data-finish-path="{{ $task->finish_path }}"
                       qa-id="{{ $task->QA_id }}">
                    <div class="card-body px-3 py-3">
                      <div class="card-text mb-1">{{$task->name}}</div>
                      @if (@$editor)
                        <div class="card-text mb-1">
                          <span>Editor: {{$editor->email}}</span>
                          -
                          <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                        </div>
                      @endif
                      <div class="qa-details">
                        @if (@$qa)
                          <div class="card-text mb-1">
                            <span>QA: {{ $qa->email }}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                          </div>
                          <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                        @endif
                      </div>
                      <div class="button-box d-flex justify-content-between">
                        <div class="status px-2 rounded bug d-inline-block p-1 fw-semibold text-white project-name">
                          Rejected
                        </div>
                        @if ($task->instruction)
                          <div class=" px-2 rounded btn-warning d-inline-block p-1 fw-semibold text-white project-name">
                            Instruction
                          </div>
                        @endif
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
                       class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow"
                       editor-id="{{ $task->editor_id }}"
                       data-editor-check-num="{{ $task->editor_check_num }}"
                       data-finish-path="{{ $task->finish_path }}"
                       qa-id="{{ $task->QA_id }}">
                    <div class="card-body px-3 py-3">
                      <div class="card-text mb-1">{{$task->name}}</div>
                      @if (@$editor)
                        <div class="card-text mb-1">
                          <span>Editor: {{$editor->email}}</span>
                          -
                          <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                        </div>
                      @endif
                      <div class="qa-details">
                        @if (@$qa)
                          <div class="card-text mb-1">
                            <span>QA: {{ $qa->email }}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                          </div>
                          <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                        @endif
                      </div>
                      <div class="button-box d-flex justify-content-between">
                        <div
                          class="status px-2 rounded {{$task->QA_check_num ? 'bug' : 'testing'}} d-inline-block p-1 fw-semibold text-white project-name">
                          @if ($task->QA_check_num)
                            Reject resolve
                          @else
                            Testing
                          @endif
                        </div>
                        @if ($task->instruction)
                          <div class=" px-2 rounded btn-warning d-inline-block p-1 fw-semibold text-white project-name">
                            Instruction
                          </div>
                        @endif
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
                       class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow"
                       editor-id="{{ $task->editor_id }}"
                       data-editor-check-num="{{ $task->editor_check_num }}"
                       data-finish-path="{{ $task->finish_path }}"
                       qa-id="{{ $task->QA_id }}">
                    <div class="card-body px-3 py-3">
                      <div class="card-text mb-1">{{$task->name}}</div>
                      @if (@$editor)
                        <div class="card-text mb-1">
                          <span>Editor: {{$editor->email}}</span>
                          -
                          <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                        </div>
                      @endif
                      <div class="qa-details">
                        @if (@$qa)
                          <div class="card-text mb-1">
                            <span>QA: {{ $qa->email }}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                          </div>
                          <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                        @endif
                      </div>
                      <div class="button-box d-flex justify-content-between">
                        <div class="status px-2 rounded done d-inline-block p-1 fw-semibold text-white project-name">
                          No bug left
                        </div>
                        <button class="done-task px-2 text-white border-0 rounded outline-0 d-inline-block done">
                          Finish
                        </button>
                      </div>
                    </div>
                  </div>
                @endforeach

                @foreach ($tasks_finished as $task)
                  @php
                    $editor = App\Domain\Admin\Models\Admin::find($task->editor_id);
                    $qa = App\Domain\Admin\Models\Admin::find($task->QA_id);
                  @endphp
                  <div id={{$task->id}}
                    data-toggle="modal"
                       data-url="{{ route('admin.popup', $task->id) }}"
                       class="card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow"
                       editor-id="{{ $task->editor_id }}"
                       data-editor-check-num="{{ $task->editor_check_num }}"
                       data-finish-path="{{ $task->finish_path }}"
                       qa-id="{{ $task->QA_id }}">
                    <div class="card-body px-3 py-3">
                      <div class="card-text mb-1">{{$task->name}}</div>
                      @if (@$editor)
                        <div class="card-text mb-1">
                          <span>Editor: {{$editor->email}}</span>
                          -
                          <span>Start: {{ date('d/m/Y H:i', strtotime($task->start_at)) }}</span>
                        </div>
                      @endif
                      <div class="qa-details">
                        @if (@$qa)
                          <div class="card-text mb-1">
                            <span>QA: {{ $qa->email }}</span>
                            -
                            <span>Start: {{ date('d/m/Y H:i', strtotime($task->QA_start)) }}</span>
                          </div>
                          <div class="card-text mb-1">QA checked: {{$task->QA_check_num}}</div>
                        @endif
                      </div>
                      <div class="button-box d-flex justify-content-between">
                        <div class="status px-2 rounded done d-inline-block p-1 fw-semibold text-white project-name">
                          Finished
                        </div>
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
      const user_role = '{{ auth()->user()->getRoleNames()[0] }}';

      function addNewTask() {
        $.ajax({
          url: "{{ route('admin.assign-editor')}}",
          type: 'GET',
          dataType: 'json',
          success: function (response) {
            const startAt = new Date(response.start_at);

            const formatedDate = ('0' + startAt.getDate()).slice(-2);
            const formatedMonth = ('0' + (startAt.getMonth() + 1)).slice(-2);
            const formatedHour = ('0' + startAt.getHours()).slice(-2);
            const formatedMinute = ('0' + startAt.getMinutes()).slice(-2);

            const formattedStartAt = `${formatedDate}/${formatedMonth}/${startAt.getFullYear()} ${formatedHour}:${formatedMinute}`;
            const newTask = "<div id=" + response.id + " data-toggle='modal' data-url='admin/popup/" + response.id +
              "' class='card rounded-0 mb-3 border-0 border-start border-primary border-3 shadow' editor-id=" +
              response.editor_id + " qa-id=" + response.QA_id + " data-editor-check-num="+response.editor_check_num+
              " data-finish-path="+response.finish_path+">" +
              "<div class='card-body px-3 py-3'>" +
              "<div class='card-text mb-1'>" + response.name + "</div>" +
              "<div class='card-text mb-1'>" +
              "<span>Editor: {{@$editor->email}}</span>" +
              " - " +
              "<span class='start-time'>Start: </span>" +
              "</div>" +
              "<div class='qa-details'></div>" +
              "<div class='d-flex justify-content-between'>" +
              "<div class='status px-2 rounded in-progress d-inline-block p-1 fw-semibold text-white project-name'>" +
              "To do" +
              "</div>" +
              "<button class='start-task px-2 text-white border-0 rounded outline-0 d-inline-block in-progress'>Start</button>" +
              "</div>" +
              "</div>" +
              "</div>"
            if (response.name) {
              $('#in-progress').append(newTask);
            }
          },
          error: function (xhr) {
            console.log('success');
          }
        });
      }

      function assignQA(taskId, statusLabel) {
        $.ajax({
          url: "admin/assign-qa/" + taskId,
          type: "GET",
          dataType: 'json',
          success: function (response) {
            if (response.QA) {
              //nếu có QA online
              addNewTask();

              const qaStart = new Date(response.task.QA_start);

              const formatedDate = ('0' + qaStart.getDate()).slice(-2);
              const formatedMonth = ('0' + (qaStart.getMonth() + 1)).slice(-2);
              const formatedHour = ('0' + qaStart.getHours()).slice(-2);
              const formatedMinute = ('0' + qaStart.getMinutes()).slice(-2);

              const formattedQaStart = `${formatedDate}/${formatedMonth}/${qaStart.getFullYear()} ${formatedHour}:${formatedMinute}`;
              const qaDetails = "<div class='card-text mb-1'>" +
                "<span>QA: " + response.QA.email + "</span>" +
                " - " +
                "<span>Start: " + formattedQaStart + "</span>" +
                "</div>" +
                "<div class='card-text mb-1'>QA checked: " + (response.task.QA_check_num ? response.task.QA_check_num : '') + "</div>"
              $('#' + taskId).find('.qa-details').append(qaDetails);
              $('#' + taskId).attr('qa-id', response.QA.id);
              statusLabel.text('Testing');
              statusLabel.css("background-color", "#ebc334");
            } else {
              // nếu chạy vào đây tức là không có QA nào online
              const columnId = '#testing'
              const message_drop = 'Hiện tại không có QA nào online. Hãy liên hệ với admin để tìm cách giải quyết.'
              $('#in-progress').sortable('cancel').sortable('cancel');
              alert(message_drop);
              statusLabel.text('In progress');
              statusLabel.css("background-color", "#1890ff");
            }
          },
          error: function (xhr) {
            console.log(xhr.responseText);
          }
        })
      }

      function checkStatus(id) {
        status = $('#' + id).find('.status').text();
        return $.trim(status);
      }

      function cancelDrop(columnId, message_drop) {
        $(columnId).sortable('cancel');
        alert(message_drop);
      }

      function checkRejected() {
        let result = false
        $('#in-progress').children().each(function (index, child) {
          if ($.trim($(child).find('.status').text()) === 'Rejected') {
            result = true
            return false
          }
          ;
        });
        return result
      }

      $('.sortable').sortable({
        connectWith: ".sortable",
        placeholder: "task-placeholder",
        start: function (event, ui) {
          ui.item.toggleClass("dragging");
          taskId = ui.item.attr('id');

          // check xem có còn task bug không
          let hasRejected = checkRejected();
          let is_inProgress = checkStatus(taskId) == 'In progress';
          // truyền xuống before stop
          ui.item.data('hasRejected', hasRejected);
          ui.item.data('is_inProgress', is_inProgress);

          //check xem có phải task todo không
          let is_todo = checkStatus(taskId) == 'To do';
          ui.item.data('is_todo', is_todo);
        },
        beforeStop: function (event, ui) {
          //nếu còn bug thì không được kéo sang test
          let hasRejected = ui.item.data('hasRejected');
          let is_inProgress = ui.item.data('is_inProgress');
          if (hasRejected && is_inProgress && user_role != 'superadmin') { // admin ko bị chặn case này
            const message_drop = 'Bạn phải hoàn thành hết các case(s) rejected trước.';
            cancelDrop('#in-progress', message_drop);
          }

          //nếu là todo thì không được kéo sang test
          let is_todo = ui.item.data('is_todo');
          if (is_todo) {
            const message_drop = 'Bạn vẫn chưa bắt đầu làm case này.'
            cancelDrop('#in-progress', message_drop);
          }

          

          // nếu là editor chỉ được kéo từ IP sang test
          if ($(this).attr('id') != 'in-progress' && user_role == 'editor') {
            let message_drop = 'Bạn không có quyền kéo case này';
            cancelDrop('#' + $(this).attr('id'), message_drop);
          }
        },
        stop: function (event, ui) {
          ui.item.toggleClass("dragging");
        },
        receive: function (event, ui) {
          let thisProcess = $(this).attr('id');
          switch (thisProcess) {
            case 'in-progress':
              processStatus = 4;
              removeButton(ui.item.find('.button-box'));
              checkOnline(ui.item.attr('editor-id'), function (result) {
                if (result) {

                  if ($.trim(ui.item.find('.status').text()) == 'Finished') {
                    alert('Case đã hoàn thành không thể chuyển lại về edit.');
                    $('#done').sortable('cancel').sortable('cancel');
                  } else {
                    ui.item.find('.status').css("background-color", "#BB0000");
                    status = 'Rejected';
                    updateTaskStatus(taskId, processStatus);
                    ui.item.find('.status').text(status);
                  }
                } else {
                  $('#testing').sortable('cancel').sortable('cancel');
                  alert('Editor hiện tại đang không online. Liên hệ với admin để chuyển task sang Editor khác.')
                }
              });
              break;
            case 'testing':
              processStatus = 2;
              if ($.trim(ui.item.find('.status').text()) == 'Rejected') {
                  // nếu chưa có editor_check_num or finish_path thì ko kéo dc sang test
                  let editor_check_num = ui.item.attr('data-editor-check-num');
                  let finish_path = ui.item.attr('data-finish-path');
                  if (editor_check_num == '' || finish_path == '') {
                      let message_drop = 'Bạn chưa điền số lượng ảnh done hoặc đường dẫn file done';
                      $('#in-progress').sortable('cancel').sortable('cancel');
                      alert(message_drop);
                      break;
                  }
                checkOnline(ui.item.attr('qa-id'), function (result) {
                  if (result) {
                    status = 'Reject resolve';
                    ui.item.find('.status').css("background-color", "#BB0000")
                    removeButton(ui.item.find('.button-box'));
                    updateTaskStatus(taskId, processStatus);
                    ui.item.find('.status').text(status);
                  } else {
                    $('#in-progress').sortable('cancel').sortable('cancel');
                    alert('QA hiện tại đang không online. Liên hệ với admin để chuyển task sang Editor khác.')
                  }
                });
              } else if ($.trim(ui.item.find('.status').text()) == 'Finished') {
                $('#done').sortable('cancel').sortable('cancel');
                alert('Case đã hoàn thành không thể chuyển lại về test.');
              } else if (ui.item.has(".in-progress").length && ui.item.find(".qa-details").find('div').length == 0) {
                //chỉ trường hợp task in-progress mới append html qaDetails
                let statusLabel = ui.item.find('.status');
                assignQA(taskId, statusLabel);
              } else if ($.trim(ui.item.find('.status').text()) == 'No bug left') {
                status = 'Reject resolve';
                removeButton(ui.item.find('.button-box'));
                updateTaskStatus(taskId, processStatus);
                ui.item.find('.status').css("background-color", "#BB0000");
                ui.item.find('.status').text(status);
              } else {
                // nếu chưa có editor_check_num or finish_path thì ko kéo dc sang test
                let editor_check_num = ui.item.attr('data-editor-check-num');
                let finish_path = ui.item.attr('data-finish-path');
                if (editor_check_num == '' || finish_path == '') {
                  let message_drop = 'Bạn chưa điền số lượng ảnh done hoặc đường dẫn file done';
                  $('#in-progress').sortable('cancel').sortable('cancel');
                  alert(message_drop);
                }else{
                  status = 'Testing';
                  removeButton(ui.item.find('.button-box'));
                  updateTaskStatus(taskId, processStatus);
                  ui.item.find('.status').css("background-color", "#ebc334");
                  ui.item.find('.status').text(status);
                  addNewTask();
                }
              }
              break;
            case 'done':
              processStatus = 3;
              if ($.trim(ui.item.find('.status').text()) == 'In progress' || $.trim(ui.item.find('.status').text()) == 'Rejected') {
                alert('Bạn phải assign sang Testing trước.')
                $('#in-progress').sortable('cancel');
              } else {
                status = 'No bug left';
                ui.item.find('.status').css("background-color", "#458B00")
                qaToDone(ui.item.find('.button-box'));
                updateTaskStatus(taskId, processStatus);
                ui.item.find('.status').text(status);
              }
            default:
              break;
          }

        }
      });

      function qaToDone(buttonBox) {
        const buttonFinish = "<button class='done-task px-2 text-white border-0 rounded outline-0 d-inline-block done'>Finish</button>";
        buttonBox.append(buttonFinish);
      }

      function removeButton(buttonBox) {
        buttonBox.find('.done-task').remove();
      }

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
          success: function (response) {
            // Xử lý thành công
            console.log(response);
          },
          error: function (xhr) {
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

        const formatedDate = ('0' + currentTime.getDate()).slice(-2);
        const formatedMonth = ('0' + (currentTime.getMonth() + 1)).slice(-2);
        const formatedHour = ('0' + currentTime.getHours()).slice(-2);
        const formatedMinute = ('0' + currentTime.getMinutes()).slice(-2);

        let formated_time = 'Start: ' + formatedDate + '/' + formatedMonth + '/' + currentTime.getFullYear() + ' ' + formatedHour + ':' + formatedMinute;
        updateTaskStatus(this_id, 1, saveStartAt);
        $(this).siblings('.status').text('In progress');
        $(this).parents().siblings('.card-text').find('.start-time').text(formated_time);
        $(this).remove();
      })

      //finish task
      $(document).on('click', '.done-task', function (event) {
        event.stopPropagation();
        let text = "Tác vụ này sẽ không thể đảo ngược. Xác nhận case đã hoàn thành?";
        if (confirm(text) == true) {
          let this_id = $(this).parents('.card').attr('id');
          updateTaskStatus(this_id, 6);
          $(this).siblings('.status').text('Finished');
          $(this).remove();
        }
      })

      // when click on task, show popup
      $(document).on('click', '.dashboard-case .card', function () {
        var popupUrl = $(this).data('url');

        $.ajax({
          type: 'Get',
          url: popupUrl,
          success: function (res) {
            if (res.status == 'error') {
              showMessage('error', res.message)
            } else {
              $('#popupContainer').html(res)
              $('#popup').modal('show');
            }
          }
        })
      })

      function checkOnline(UserId, callback) {
        var result;
        $.ajax({
          url: "admin/check-online/" + UserId,
          type: "GET",
          dataType: 'json',
          success: function (response) {
            callback(response);
          },
          error: function (xhr) {
            console.log(xhr.responseText);
          }
        })
      }
    })
  </script>
@endpush
