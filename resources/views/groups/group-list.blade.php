<div up-expand up-scroll="false" class="d-flex items-start pb-3 mb-3 border-bottom">

    @if ($group->hasCover())
        <img class="rounded" width="150" src="{{ route('groups.cover.medium', $group) }}" />
    @else
        <img class="rounded" width="150" src="/images/group.svg" />
    @endif

    <div class="mx-2 flex-grow ">

        <div class="">
            <a href="{{ route('groups.show', $group) }}">
                {{ summary($group->name) }}
            </a>
        </div>

        @if ($group->tags->count() > 0)
            <div class="d-flex gap-1 flex-wrap">
                @foreach ($group->tags as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif

        <div class="">
            {{ summary($group->body, 100) }}
        </div>

        <div class="text-meta">
            {{ trans('created at') }}
            {{ $group->created_at->diffForHumans() }} -
            {{ trans('updated at') }}
            {{ $group->updated_at->diffForHumans() }}
        </div>
    </div>

</div>
