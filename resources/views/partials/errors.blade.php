@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>{{ trans('messages.howdy') }}</strong> {{ trans('messages.something_wrong') }}<br><br>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
