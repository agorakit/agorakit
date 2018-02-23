@foreach ((array) session('flash_notification') as $message)
        <div class="alert alert-primary" role="alert">
                {!! $message['message'] !!}
        </div>
@endforeach

{{ session()->forget('flash_notification') }}
