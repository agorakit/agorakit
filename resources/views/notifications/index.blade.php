@extends('app')

@section('content')


  <div class="toolbox d-md-flex">
    <div class="d-flex mb-2">
      <h1>
        <a up-follow href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.notifications') }}
      </hi>
    </div>
  </div>


  <div class="notifications">
    @if ($notifications)
      <table class="table">
        <thead>
          <tr>
          <th>Type</th>
          <th>Message</th>
        </tr>
        </thead>
        @foreach($notifications as $notification)
          <tr>
            <td>
              {{$notification->type}}
            </td>

            <td>
              @if ($notification->type == 'App\Notifications\GroupCreated')
                @include('notifications.group_created')
              @endif

              @if ($notification->type == 'App\Notifications\MentionedUser')
                @include('notifications.mentioned_user')
              @endif

            </td>
          </tr>
        @endforeach
      </table>
      {!! $notifications->render() !!}
    @else
      {{trans('messages.nothing_yet')}}
    @endif
  </div>


@endsection
