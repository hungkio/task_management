<!-- Modal -->
<div class="modal fade" id="popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ $task->name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="row">
            <div class="col">
              <label for="">Editor:</label>
              <input class="form-control" type="text" value="{{ $editor ? ($editor->last_name . ' ' . $editor->first_name) : '' }}" disabled/>
            </div>
            <div class="col">
              <label for="">Estimate:</label>
              <input class="form-control" type="text" value="{{ $task->estimate }}" name="estimate" {{ ($roleName == 'admin' || $roleName == 'superadmin') ? '' : 'disabled' }}/>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col">
              <label for="">Start:</label>
              <input class="form-control" type="text" value="{{ date('d/m/Y H:i', strtotime($task->start_at)) }}" name="start_at" disabled/>
            </div>
            <div class="col">
              <label for="">End:</label>
              <input class="form-control" type="text" value="{{$task->end_at ? date('d/m/Y H:i', strtotime($task->end_at)) : null }}" name="end_at" disabled/>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="">Path:</label>
          <input class="form-control" type="text" value="{{$task->path}}" name="path" disabled />
        </div>
        <div class="form-group">
          <label for="">Count Record:</label>
          <input class="form-control" type="text" value="{{$task->countRecord}}" disabled/>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col">
              <label for="">QA:</label>
              <input class="form-control" type="text" value="{{ $QA ? ($QA->last_name . ' ' . $QA->first_name) : '' }}" disabled/>
            </div>
            <div class="col">
              <label for="">Check Num:</label>
              <input class="form-control" type="text" value="{{ $task->QA_check_num }}" name="QA_check_num" />
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col">
              <label for="">QA Start:</label>
              <input class="form-control" type="text" value="{{$task->QA_start ? date('d/m/Y H:i', strtotime($task->QA_start)) : null }}" disabled/>
            </div>
            <div class="col">
              <label for="">QA End:</label>
              <input class="form-control" type="text" value="{{$task->QA_end ? date('d/m/Y H:i', strtotime($task->QA_end)) : null }}" disabled/>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="">QA Note:</label>
          <input class="form-control" type="text" value="{{$task->QA_note}}" name="QA_note" />
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="redo" name="redo">
          <label class="form-check-label" for="redo">Redo</label>
        </div>
        <div class="form-group">
          <label for="">Reasons:</label>
          <input id="reason" class="form-control" type="text" name="redo_note" disabled/>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#redo').on('change', function() {
      if ($(this).is(':checked')) {
          $('#reason').prop('disabled', false);
      } else {
          $('#reason').prop('disabled', true);
          $('#reason').val('');
      }
    });
  })
</script>