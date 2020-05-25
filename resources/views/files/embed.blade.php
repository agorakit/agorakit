<div class="card" style="max-width: 15rem;">
@if ($file->isImage())
<a href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
<img src="{{ route('groups.files.preview', [$file->group, $file]) }}" class="card-img-top img-fluid" style="object-fit: cover; max-height: 10rem">
</a>
@endif
<div class="card-body">
<h5 class="card-title">
<img src="{{ route('groups.files.icon', [$file->group, $file]) }}" style="width:1rem; height: 1rem">
<a up-follow href="{{ route('groups.files.show', [$file->group, $file]) }}">
{{ $file->name }}
</a>
</h5>
<p class="card-text">
<small>
<div>
<a up-follow href="{{ route('users.show', [$file->user]) }}">
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
<div>
@if ($file->isLink())
<i class="fas fa-link"></i> {{$file->path}}
@endif
</div>
</small>
</p>

@if ($file->isLink())
<a class="mr-1" href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank"  class="btn btn-primary btn-sm">
{{trans('messages.visit')}}
</a>
@else
<a href="{{ route('groups.files.download', [$file->group, $file]) }}"  class="btn btn-primary btn-sm" target="_blank">
{{trans('messages.download')}}
</a>
@endif
</a>
</div>
</div>
