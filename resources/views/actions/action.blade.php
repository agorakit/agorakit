<div class="flex items-center py-3 border-b border-gray-300 hover:bg-gray-100" up-expand>
    <div
        class="border-gray-200 text-gray-600 border-2 flex-shrink-0 flex flex-col items-center justify-center h-12 w-12 rounded-lg mx-1">
        <div class="text-xl -mb-2 text-gray-800">{{ $action->start->format('d') }}</div>
        <div class="text-sm">{{ $action->start->format('M') }}</div>
    </div>


    <div class="mx-2">
        <div class="text-gray-800">
            <a up-follow
                href="{{ route('groups.actions.show', [$action->group, $action]) }}">
                {{ $action->name }}
            </a>
        </div>
        <div class="flex align-middle text-gray-600 text-xs">
            <i class="ri-user-line mr-2"></i> {{ $action->attending->count() }}
            {{ trans('participants') }}
        </div>

        <div class="flex align-middle text-gray-600 text-xs">
            <i class="ri-time-line mr-2"></i> {{ $action->start->format('H:i') }} -
            {{ $action->stop->format('H:i') }}
        </div>

        <div class="flex align-middle text-gray-600 text-xs">
            <i class="ri-map-line mr-2"></i> {{ $action->location }}
        </div>
    </div>
</div>