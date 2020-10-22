<div up-expand>
    <div class="@if ($group->isArchived()) status-archived @endif">


        
        <a up-follow href="{{ action('GroupController@show', $group) }}">
            @if($group->hasCover())
                <img class="h-48 w-full object-cover"
                    src="{{ route('groups.cover.medium', $group) }}" />
            @else
                <img class="h-48 w-full object-cover" src="/images/group.svg" />
            @endif
        </a>

        

        <div class="p-3">
            <h2 class="text-base text-gray-700">
                <a up-follow href="{{ action('GroupController@show', $group) }}">
                    {{ $group->name }}
                </a>
                @if($group->isOpen())
                    <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
                @elseif($group->isClosed())
                    <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                @else
                    <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
                @endif

                @if($group->isPinned())
                    <div class="badge badge-primary" style="min-width: 2em; margin: 0 2px; font-size: 0.5em;">
                        {{ __('Pinned') }}</div>
                @endif
                @if($group->isArchived())
                    <div class="badge badge-muted" style="min-width: 2em; margin: 0 2px; font-size: 0.5em;">
                        {{ __('Archived') }}</div>
                @endif

            </h2>

            <div class="text-xs text-gray-600">
                {{ summary($group->body) }}
            </div>

            <span class="badge badge-secondary"><i class="fa fa-users"></i> {{ $group->users()->count() }}</span>
            <span class="badge badge-secondary"><i class="fa fa-comments"></i>
                {{ $group->discussions()->count() }}</span>
            <span class="badge badge-secondary"><i class="fa fa-calendar"></i>
                {{ $group->actions()->count() }}</span>
            
            <div style="max-height:3.8em; overflow: hidden">
                @foreach($group->tags as $tag)
                    @include('tags.tag')
                @endforeach
            </div>


            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="btn-group">
                    <a class="btn btn-primary"
                        href="{{ action('GroupController@show', $group) }}"></i>
                        {{ trans('messages.visit') }}
                    </a>
                </div>
                <small class="text-muted">{{ $group->updated_at->diffForHumans() }}</small>
            </div>



        </div>
    </div>
</div>