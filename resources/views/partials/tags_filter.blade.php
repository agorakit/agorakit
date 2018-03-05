
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{trans('messages.filter')}}
        @if (request()->get('tag'))
            : {{request()->get('tag')}}
        @endif
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a up-target="files" class="dropdown-item" href="?tag=">{{trans('messages.show_all')}}</a>
        <div class="dropdown-divider"></div>
        @foreach ($tags as $id=>$tag)
            <a up-target="files" class="dropdown-item" href="?tag={{$tag}}">{{$tag}}</a>
        @endforeach
    </div>
</div>
