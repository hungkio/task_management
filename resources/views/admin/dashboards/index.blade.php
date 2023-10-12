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

    .task-column > .in-progress,
    .task-column > .to-do,
    .task-column > .start-task {
      background-color: #3f2e02;
      position: relative;
      justify-content: center;
    }
    .task-column > .testing {
      background-color: #3f2e02;
      position: relative;
      justify-content: center;
    }

    .task-column > .done,
    .task-column > .no-bug {
      background-color: #3f2e02;
      position: relative;
      justify-content: center;
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
    .counter{
      color: white;
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      display: flex;
      align-items: center;
      padding: 0 20px;
      font-size: 18px;
    }
    .counter:empty{
      display: none;
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
      .modal-content img {
          max-width: 100%;
      }
    .customer_styles img {
        max-height: 400px;
    }
    div.deadline {
        position: relative;
    }
    .countdown span.expired {
        background: red;
    }
  </style>
@endpush

@section('page-content')
<div id="popupContainer"></div>
<section class="dashboard-case">
  <div class="d-flex flex-column mb-4 p-2 bg-white shadow-sm">
    <form method="GET" action="{{route('admin.dashboards')}}">
      @csrf
      <label for="filter-by-user">Filter:</label>
      <input type="text" name="filter-by-user" placeholder="username or customer" value="{{$input_filter}}">
      <button class="outline-0 border shadow-sm" type="submit">Submit</button>
    </form>
  </div>
  <div class="position-relative">
    <div class="">
      <div class="dashboard d-flex justify-content-between">
        <!-- To do tasks -->
        <div class="task-column w-100 shadow-sm bg-white">
          <div class="in-progress p-2 text-white d-flex">
            <span class="column-heading">
              In Progress
            </span>
            <span id="in-progress-counter" class="counter"></span>
          </div>
          <div class="taskList m-2 bg-light flex-grow-1">
            <div id="in-progress" class="sortable h-100 d-flex flex-column">
              <!-- card -->
              <!-- render task todo -->
              @foreach ($tasks_todo as $task)
                @php
                  $editor = $task->editor;
                  $qa = $task->QA;
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
                    <div class="card-text mb-1 deadline">
                        {{$task->name}}
                    </div>
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
                        class="status to-do d-inline-block project-name">
                        To do
                      </div>
                      @if ($task->instruction)
                        <div class="d-inline-block project-name instruction">
                          Instruction
                        </div>
                      @endif
                        @if ($task->redo)
                            <div class="btn-danger d-inline-block project-name">
                                Redo
                            </div>
                        @endif
                        @if ($task->redo_note)
                            <div class="d-inline-block project-name bad">
                                Bad
                            </div>
                        @endif
                        <button
                          class="start-task px-2 text-white border-0 rounded outline-0 d-inline-block">Start
                        </button>
                        @if($task->deadline)
                            <?php
                            $isLate = false;
                            $hour = date_create_from_format('Y-m-d H:i', $task->deadline)->format('H');
                            $now = date('Y-m-d H:i');

                            if ($now > $task->deadline) {
                                $isLate = true;
                            }
                            ?>
                            <span class="deadline @if($isLate) late @endif">{{ "$hour:00" }} {{ ($hour > 12 )? "PM":"AM" }}</span>
                        @endif
                    </div>
                  </div>
                </div>
              @endforeach
            <!-- render task editing -->
              @foreach ($tasks_editing as $task)
                @php
                  $editor = $task->editor;
                  $qa = $task->QA;
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
                    <div class="card-text mb-1 deadline">
                      {{$task->name}}
                    </div>
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
                        class="status in-progress d-inline-block project-name">
                        In progress
                      </div>
                      @if ($task->instruction)
                        <div
                          class="d-inline-block project-name instruction">
                          Instruction
                        </div>
                      @endif
                      @if ($task->redo)
                          <div class="d-inline-block project-name">
                              Redo
                          </div>
                      @endif
                      @if ($task->redo_note)
                          <div class="d-inline-block project-name bad">
                              Bad
                          </div>
                      @endif
                      <div class="time-box">
                        <div class="countdown"
                            data-deadline="{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $task->start_at)->addMinutes($task->estimate*($task->countRecord*$task->AX->real_amount ?? 1))->format('Y-m-d H:i:s') }}">
                        </div>
                        @if($task->deadline)
                              <?php
                              $isLate = false;
                              $hour = date_create_from_format('Y-m-d H:i', $task->deadline)->format('H');
                              $now = date('Y-m-d H:i');

                              if ($now > $task->deadline) {
                                  $isLate = true;
                              }
                              ?>
                            <span class="deadline @if($isLate) late @endif">{{ "$hour:00" }} {{ ($hour > 12 )? "PM":"AM" }}</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            <!-- render task bug -->
              @foreach ($tasks_rejected as $task)
                @php
                  $editor = $task->editor;
                  $qa = $task->QA;
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
                    <div class="card-text mb-1 deadline">
                        {{$task->name}}
                    </div>
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
                      <div class="status bug d-inline-block project-name">
                        Rejected
                      </div>
                      @if ($task->instruction)
                        <div class="d-inline-block project-name instruction">
                          Instruction
                        </div>
                      @endif
                      @if ($task->redo)
                          <div class="d-inline-block project-name">
                              Redo
                          </div>
                      @endif
                      @if ($task->redo_note)
                          <div class="d-inline-block project-name bad">
                              Bad
                          </div>
                      @endif
                      <div class="time-box">
                        <div class="countdown" data-deadline="{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $task->start_at)->addMinutes($task->estimate*($task->countRecord*$task->AX->real_amount ?? 1))->format('Y-m-d H:i:s') }}"></div>
                        @if($task->deadline)
                            <?php
                            $isLate = false;
                            $hour = date_create_from_format('Y-m-d H:i', $task->deadline)->format('H');
                            $now = date('Y-m-d H:i');

                            if ($now > $task->deadline) {
                                $isLate = true;
                            }
                            ?>
                            <span class="deadline @if($isLate) late @endif">{{ "$hour:00" }} {{ ($hour > 12 )? "PM":"AM" }}</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <!-- Testing tasks -->
        <div class="task-column w-100 shadow-sm bg-white">
          <div class="testing p-2 text-white d-flex">
            <span class="column-heading">
              Testing
            </span>
            <span id="testing-counter" class="counter"></span>
          </div>
          <div class="taskList m-2 bg-light flex-grow-1">
            <div id="testing" class="sortable d-flex flex-column h-100">
              <!-- card -->
              @foreach ($tasks_testing as $task)
                @php
                  $editor = $task->editor;
                  $qa = $task->QA;
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
                    <div class="card-text mb-1 deadline">
                        {{$task->name}}
                    </div>
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
                        class="status {{$task->QA_check_num ? 'bug' : 'testing'}} d-inline-block project-name">
                        @if ($task->QA_check_num)
                          Reject resolve
                        @else
                          Testing
                        @endif
                      </div>
                      @if ($task->instruction)
                        <div class="d-inline-block project-name instruction">
                          Instruction
                        </div>
                      @endif
                      @if ($task->redo)
                          <div class="d-inline-block project-name">
                              Redo
                          </div>
                      @endif
                      @if ($task->redo_note)
                          <div class="d-inline-block project-name bad">
                              Bad
                          </div>
                      @endif
                      <div class="time-box">

                        @if($task->QA_start)
                        <div class="countdown" data-deadline="{{ \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i' , $task->QA_start)->addMinutes($task->estimate_QA*($task->countRecord*$task->AX->real_amount ?? 1))->format('Y-m-d H:i:s') }}"></div>
                        @endif
                        @if($task->deadline)
                          <?php
                          $isLate = false;
                          $hour = date_create_from_format('Y-m-d H:i', $task->deadline)->format('H');
                          $now = date('Y-m-d H:i');

                          if ($now > $task->deadline) {
                              $isLate = true;
                          }
                          ?>
                          <span class="deadline @if($isLate) late @endif">{{ "$hour:00" }} {{ ($hour > 12 )? "PM":"AM" }}</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach

            </div>
          </div>
        </div>
        <!-- tasks completed -->
        <div class="task-column w-100 shadow-sm bg-white">
          <div class="done p-2 text-white d-flex">
            <span class="column-heading">
              Done
            </span>
            <span id="done-counter" class="counter"></span>
          </div>
          <div class="taskList m-2 bg-light flex-grow-1">
            <div id="done" class="sortable d-flex flex-column h-100">
              <!-- card -->
              @foreach ($tasks_done as $task)
                @php
                  $editor = $task->editor;
                  $qa = $task->QA;
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
                    <div class="card-text mb-1 deadline">
                        {{$task->name}}
                    </div>
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
                      <div class="status px-2 no-bug rounded d-inline-block project-name">
                        No bug left
                      </div>
                      @if ($task->instruction)
                          <div class="d-inline-block project-name instruction">
                              Instruction
                          </div>
                      @endif
                      @if ($task->redo)
                          <div class="d-inline-block project-name">
                              Redo
                          </div>
                      @endif
                      @if ($task->redo_note)
                          <div class="d-inline-block project-name bad">
                              Bad
                          </div>
                      @endif
                      @if ($task->excellent)
                          <div class="d-inline-block project-name excellent">
                              Excellent
                          </div>
                      @endif
                      <button class="done-task border-0 outline-0 d-inline-block done">
                        Finish
                      </button>
                      @if($task->deadline)
                          <?php
                          $isLate = false;
                          $hour = date_create_from_format('Y-m-d H:i', $task->deadline)->format('H');
                          if (strtotime($task->QA_end) > strtotime($task->deadline)) {
                              $isLate = true;
                          }
                          ?>
                          <span class="deadline @if($isLate) late @endif">{{ "$hour:00" }} {{ ($hour > 12 )? "PM":"AM" }}</span>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach

              @foreach ($tasks_finished as $task)
                @php
                  $editor = $task->editor;
                  $qa = $task->QA;
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
                    <div class="card-text mb-1 deadline">
                        {{$task->name}}
                    </div>
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
                        @if ($task->instruction)
                            <div class="d-inline-block project-name instruction">
                                Instruction
                            </div>
                        @endif
                            @if ($task->redo)
                                <div class="d-inline-block project-name">
                                    Redo
                                </div>
                            @endif
                            @if ($task->redo_note)
                                <div class="d-inline-block project-name bad">
                                    Bad
                                </div>
                            @endif
                            @if ($task->excellent)
                                <div class="d-inline-block project-name excellent">
                                    Excellent
                                </div>
                            @endif
                      <div class="status px-2 rounded done d-inline-block project-name">
                        Finished
                      </div>
                      @if($task->deadline)
                            <?php
                            $isLate = false;
                            $hour = date_create_from_format('Y-m-d H:i', $task->deadline)->format('H');
                            if (strtotime($task->QA_end) > strtotime($task->deadline)) {
                                $isLate = true;
                            }
                            ?>
                            <span class="deadline @if($isLate) late @endif">{{ "$hour:00" }} {{ ($hour > 12 )? "PM":"AM" }}</span>
                        @endif
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

        $('.countdown').each(function (key, val) {
            if ($(val).data('deadline')) {
                var end = new Date($(val).data('deadline'));

                var _second = 1000;
                var _minute = _second * 60;
                var _hour = _minute * 60;
                var _day = _hour * 24;
                var timer;

                function showRemaining() {
                    var now = new Date();
                    var distance = end - now;
                    let html = '<span class="';
                    if (distance < 0) {
                        html+= 'expired';
                    }
                    var days = Math.floor(distance / _day);
                    var hours = Math.floor((distance % _day) / _hour);
                    var minutes = Math.abs(Math.floor((distance % _hour) / _minute));
                    var seconds = Math.abs(Math.floor((distance % _minute) / _second));

                    html += '">' + hours + ':' + minutes + ':' + seconds + '</span>';
                    $(val).html(html)
                }
                timer = setInterval(showRemaining, 1000);
            }
        })

      function taskCounter() {
        let inProgress = 'in-progress';
        let testing = 'testing';
        let done = 'done';

        let columnIds = [inProgress, testing, done];
        columnIds.forEach(columnId => {
          total = $('#'+columnId).children().length;
          taskAmount = $('#'+columnId).find('.'+columnId).length;
          if(total > 0){
            $('#'+columnId + '-counter').text(taskAmount+'/'+total);
          }else{
            $('#'+columnId + '-counter').text('');
          }
        });
      }
      taskCounter();

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
              "<div class='status px-2 rounded to-do d-inline-block project-name'>" +
              "To do" +
              "</div>" +
              "<button class='start-task px-2 text-white border-0 rounded outline-0 d-inline-block'>Start</button>" +
              "</div>" +
              "</div>" +
              "</div>"
            if (response.name) {
              $('#in-progress').append(newTask);
              taskCounter();
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
              statusLabel.addClass('testing');
              statusLabel.removeClass('in-progress');
              taskCounter();
            } else {
              // nếu chạy vào đây tức là không có QA nào online
              const columnId = '#testing'
              const message_drop = 'Hiện tại không có QA nào online hoặc phù hợp. Hãy liên hệ với admin để tìm cách giải quyết.'
              $('#in-progress').sortable('cancel').sortable('cancel');
              alert(message_drop);
              statusLabel.text('In progress');
              statusLabel.addClass('in-progress');
              statusLabel.removeClass('testing');
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
        console.log('it worked');
      }

      function findStatus(status) {
        let result = false
        $('#in-progress').children().each(function (index, child) {
          if ($.trim($(child).find('.status').text()) === status) {
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
          let hasRejected = findStatus('Rejected');
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
                    ui.item.find('.status').addClass('bug');
                    ui.item.find('.status').removeClass('done');
                    status = 'Rejected';
                    updateTaskStatus(taskId, processStatus);
                    ui.item.find('.status').text(status);
                    taskCounter();
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
                    cancelDrop('#in-progress', message_drop);
                  }else{
                    checkOnline(ui.item.attr('qa-id'), function (result) {
                      if (result) {
                        status = 'Reject resolve';
                        ui.item.find('.status').addClass('bug');
                        ui.item.find('.status').removeClass('testing');
                        removeButton(ui.item.find('.button-box'));
                        updateTaskStatus(taskId, processStatus);
                        ui.item.find('.status').text(status);
                      } else {
                        $('#in-progress').sortable('cancel').sortable('cancel');
                        alert('QA hiện tại đang không online. Liên hệ với admin để chuyển task sang Editor khác.')
                      }
                    });
                  }
              } else if ($.trim(ui.item.find('.status').text()) == 'Finished') {
                $('#done').sortable('cancel').sortable('cancel');
                alert('Case đã hoàn thành không thể chuyển lại về test.');
              } else if (ui.item.has(".in-progress").length && ui.item.find(".qa-details").find('div').length == 0) {
                //chỉ trường hợp task in-progress mới append html qaDetails
                let editor_check_num = ui.item.attr('data-editor-check-num');
                let finish_path = ui.item.attr('data-finish-path');
                if (editor_check_num == '' || finish_path == '' || editor_check_num == null || finish_path == null) {
                  let message_drop = 'Bạn chưa điền số lượng ảnh done hoặc đường dẫn file done';
                  cancelDrop('#in-progress', message_drop);
                } else {
                  let statusLabel = ui.item.find('.status');
                  assignQA(taskId, statusLabel);
                }
              } else if ($.trim(ui.item.find('.status').text()) == 'No bug left') {
                status = 'Reject resolve';
                removeButton(ui.item.find('.button-box'));
                updateTaskStatus(taskId, processStatus);
                ui.item.find('.status').addClass('bug');
                ui.item.find('.status').text(status);
              } else {
                // nếu chưa có editor_check_num or finish_path thì ko kéo dc sang test
                let editor_check_num = ui.item.attr('data-editor-check-num');
                let finish_path = ui.item.attr('data-finish-path');
                if (editor_check_num == '' || finish_path == '' || editor_check_num == null || finish_path == null) {
                  let message_drop = 'Bạn chưa điền số lượng ảnh done hoặc đường dẫn file done';
                  cancelDrop('#in-progress', message_drop);
                }else{
                  status = 'Testing';
                  removeButton(ui.item.find('.button-box'));
                  updateTaskStatus(taskId, processStatus);
                  ui.item.find('.status').addClass('testing');
                  ui.item.find('.status').removeClass('in-progress');
                  ui.item.find('.status').text(status);
                  console.log('run here');
                  addNewTask();
                  taskCounter();
                }
              }
              taskCounter();
              break;
            case 'done':
              processStatus = 3;
              if ($.trim(ui.item.find('.status').text()) == 'In progress' || $.trim(ui.item.find('.status').text()) == 'Rejected') {
                alert('Bạn phải assign sang Testing trước.')
                $('#in-progress').sortable('cancel');
              } else {
                status = 'No bug left';
                ui.item.find('.status').addClass('no-bug');
                ui.item.find('.status').removeClass('testing');
                qaToDone(ui.item.find('.button-box'));
                updateTaskStatus(taskId, processStatus);
                ui.item.find('.status').text(status);
              }
              taskCounter();
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
        $(this).siblings('.status').removeClass('to-do');
        $(this).siblings('.status').addClass('in-progress');
        $(this).parents().siblings('.card-text').find('.start-time').text(formated_time);
        $(this).remove();
        taskCounter();
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

      $(document).on('click', '#greetings', function() {
        $('#greetings-popup').modal('show');
        console.log('hehe');
      })
    })
  </script>
@endpush
