<!-- Success Session Message -->
@if ($message = Session::get('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> {{ $message }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Warning Session Message -->
@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> {{ $message }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Validation issue -->
@if (count($errors) > 0)
<div class="alert alert-danger">
  <strong>Whoops!</strong> There were some problems with your input.<br><br>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<!-- Alert problem -->
@if ($message = Session::get('alert'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

@if(session('errors') && session('errors')->has('subscribed') && session('errors')->first('subscribed'))
<div class="alert alert-danger">
    {{ session('errors')->first('message') }}
</div>
@endif

@if ($message = Session::get('prompt'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Warning!</strong> {{ $message }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($message = Session::get('alert-success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> {{ $message }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
