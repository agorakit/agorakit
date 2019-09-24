<div class="file-embed">
  <div class="thumbnail">
    @if ($file->isLink())
      <a class="mr-1" href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
        <img src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}"/>
      </a>
    @else
      <a href="{{ route('groups.files.show', [$file->group, $file]) }}">
        <img src="{{ route('groups.files.preview', [$file->group, $file]) }}"/>
      </a>
    @endif
  </div>

  <div class="name">
    {{ $file->name }}
  </div>

</div>
