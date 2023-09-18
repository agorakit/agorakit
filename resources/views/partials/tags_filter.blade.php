@if ($tags->count() > 0)
    <div class="dropdown block">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            {{ trans('messages.filter') }}
            @if (request()->get('tag'))
                : {{ request()->get('tag') }}
            @endif
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a up-target=".items" class="dropdown-item"
                href="{{ request()->fullUrlWithQuery(['tag' => null, 'page' => null]) }}">{{ trans('messages.show_all') }}</a>
            <div class="dropdown-divider"></div>

            @foreach ($tags as $tag)
                <a up-target=".items" class="dropdown-item"
                    href="{{ request()->fullUrlWithQuery(['tag' => $tag->name, 'page' => null]) }}">
                    <span class="inline-block w-2 h-2 rounded-sm" style="background-color: {{ $tag->color }}"></span>
                    {{ $tag->name }}
                </a>
            @endforeach

        </div>
    </div>
@endif
