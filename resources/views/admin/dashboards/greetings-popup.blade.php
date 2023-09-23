<!-- Modal -->
@php
  $greeting = Cache::get('greeting');
@endphp
<div class="modal fade" id="greetingsPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Edit Greetings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.save.greeting') }}" method="post">
          @csrf
          <div class="form-group">
            <label for="exampleInputEmail1">Greetings</label>
            <input type="text" class="form-control" name="greeting" placeholder="Enter message" value="{{ $greeting ? $greeting['greeting_text'] : '' }}">
            <small id="help" class="form-text text-muted">This text would be shown on Dashboard.</small>
          </div>
          <div class="form-group">
            <label for="exampleColorInput" class="form-label">Text Color</label>
            <input style="width: 100px;" type="color" name="text_color" class="form-control form-control-color" id="exampleColorInput" value="{{ $greeting ? $greeting['text_color'] : '' }}" title="Choose your color">
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>