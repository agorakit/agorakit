<div class="file">
    <div class="thumbnail">
        @if ($file->isLink())
            <a href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
                <i class="fa fa-link" aria-hidden="true" style="font-size:1.8rem; color: black"></i>
            </a>
        @else
            <a href="{{ route('groups.files.show', [$file->group, $file]) }}">
                <img src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}"/>
            </a>
        @endif
    </div>

    <div class="content w-100">
        <span class="name">
            @if ($file->isLink())
                <a href="{{ route('groups.files.download', [$file->group, $file]) }}" target="_blank">
                    {{ $file->name }}
                    <i class="fa fa-external-link"></i>
                </a>
            @else
                <a  href="{{ route('groups.files.show', [$file->group, $file]) }}">{{ $file->name }}</a>
            @endif
        </span>


        @if ($file->tags->count() > 0)
            @foreach ($file->tags as $tag)
                <span class="badge badge-secondary">{{$tag->name}}</span>
            @endforeach
        @endif


        <div class="d-flex justify-content-between">
            <a href="{{ route('groups.show', [$file->group_id]) }}">
                <span class="badge badge-secondary badge-group">
                    @if ( $file->group->isPublic())
                        <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                    @else
                        <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                    @endif
                    {{ $file->group->name }}
                </span>
            </a>
            <small>{{ $file->updated_at->diffForHumans() }}</small>
            <small>@if ($file->isFile()){{sizeForHumans($file->filesize)}}@endif</small>

                <div class="ml-4 dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-wrench" aria-hidden="true"></i>

                    </button>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                        @can('update', $file)
                            <a class="dropdown-item" href="{{ route('groups.files.edit', [$file->group, $file]) }}">
                                <i class="fa fa-pencil"></i>
                                {{trans('messages.edit')}}
                            </a>
                        @endcan

                        @can('delete', $file)
                            <a class="dropdown-item" href="{{ route('groups.files.deleteconfirm', [$file->group, $file]) }}">
                                <i class="fa fa-trash"></i>
                                {{trans('messages.delete')}}
                            </a>
                        @endcan
                    </div>
                </div>

                {{--
                @if ($file->revisionHistory->count() > 0)
                <a class="dropdown-item" href="{{route('groups.files.history', [$group->id, $file->id])}}"><i class="fa fa-history"></i> {{trans('messages.show_history')}}</a>
            @endif
            --}}


        </div>
    </div>
</div>
