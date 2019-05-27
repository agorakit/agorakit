<div class="file d-flex p-2 my-4" style="border: 1px solid #aaa; border-radius: 5px">
  <div class="thumbnail mr-2">
    @if ($file->isLink())
      <a class="mr-1" href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
        <i class="fa fa-link" aria-hidden="true" style="font-size:2.5rem; color: black"></i>
      </a>
    @else
      <a href="{{ route('groups.files.show', [$file->group, $file]) }}">
        <img src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}"/>
      </a>
    @endif
  </div>

  <div class="content">
    <div class="name">
        <a href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
          {{ $file->name }}
          <i class="fa fa-external-link"></i>
        </a>

      @if ($file->tags->count() > 0)
        @foreach ($file->tags as $tag)
          <a href="{{ action('TagController@show', $tag) }}"><span class="badge badge-primary">{{$tag->name}}</span></a>
        @endforeach
      @endif
    </div>

    <div class="small meta">
      <div>
        <a href="{{ route('groups.show', [$file->group_id]) }}">
          @if ($file->group->isOpen())
            <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
          @elseif ($file->group->isClosed())
            <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
          @else
            <i class="fa fa-eye-slash" title="{{trans('group.secret')}}"></i>
          @endif
          {{ $file->group->name }}
        </a>
      </div>

      <div>
        <a href="{{ route('users.show', [$file->user]) }}">
          <i class="fa fa-user-circle"></i> {{ $file->user->name }}
        </a>
      </div>

      <div>
        <i class="fa fa-clock-o"></i> {{ $file->updated_at }}
      </div>

      <div>
        @if ($file->isFile())
          <i class="fa fa-database"></i> {{sizeForHumans($file->filesize)}}
        @endif
      </div>
    </div>

  </div>


</div>
