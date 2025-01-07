<div class="d-flex items-start pb-3 mb-3 border-bottom" up-expand up-scroll="false">

    @if ($group->hasCover())
        <img alt="" class="rounded" src="{{ route('groups.cover', [$group, 'medium']) }}" width="150" />
    @else
        <img alt="" class="rounded" src="/images/group.svg" width="150" />
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
