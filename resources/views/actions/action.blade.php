<div class="flex items-center py-3 border-b border-gray-300 hover:bg-gray-100">
    <a up-follow href="{{ route('groups.actions.show', [$action->group, $action]) }}">
        <div
            class="border-gray-400 text-gray-600 bg-gray-200 border-2 flex-shrink-0 flex flex-col items-center justify-center h-12 w-12 rounded-lg mx-1">
            <div class="text-xl -mb-2 text-gray-800">{{ $action->start->format('d') }}</div>
            <div class="text-sm">{{ $action->start->format('M') }}</div>
        </div>
    </a>


    <div up-expand class="flex-grow flex">
        <div class="mx-2">
            <div class="text-gray-800">
                <a up-follow href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                    {{ $action->name }}
                </a>
            </div>
            @if ($action->attending->count() > 0)
            <div class="flex align-middle text-gray-600 text-xs">
                <i class="ri-user-line mr-2"></i> {{ $action->attending->count() }}
                {{ trans('participants') }}
            </div>
            @endif

            <div class="flex align-middle text-gray-600 text-xs">
                <i class="ri-time-line mr-2"></i> {{ $action->start->format('H:i') }} -
                {{ $action->stop->format('H:i') }}
            </div>

            <div class="flex align-middle text-gray-600 text-xs">
                <i class="ri-map-line mr-2"></i> {{ $action->location }}
            </div>

            @if($action->getSelectedTags()->count() > 0)
            <div class="text-gray-600 text-xs overflow-hidden my-1 h-5">
                @foreach($action->getSelectedTags() as $tag)
                @include('tags.tag')
                @endforeach
            </div>
            @endif


        </div>

        @if ($action->attending->count() > 0)
        <div class="flex -space-x-5 overflow-hidden ml-5">
            @foreach($action->attending as $user)
            @include('users.avatar')
            @endforeach
        </div>
        @endif
    </div>


    @can ('participate', $action)
    <div class="ml-5">
        @include('participation.dropdown')
    </div>
    @endcan


</div>