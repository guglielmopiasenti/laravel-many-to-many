@if(session('toast-message'))
<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="toast-body">
    {{ session('toast-message') }}
      <div class="mt-2 pt-2 border-top">
          <form action="{{ session('toast-project-id') }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm">Undo</button>
          </form>
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Close</button>
      </div>
  </div>
</div>
<script>
    var toastEl = document.querySelector('.toast');
    var toast = new bootstrap.Toast(toastEl);
    toast.show();
</script>
@endif