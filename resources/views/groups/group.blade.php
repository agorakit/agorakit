 <a up-follow href="{{ action('GroupController@show', $group) }}"
     class="relative rounded-md border-gray-300 border shadow-md hover:shadow-xl flex flex-col justify-start @if ($group->isArchived()) status-archived @endif">


     @if($group->hasCover())
         <img class="object-cover w-full h-48 rounded rounded-b-none"
             src="{{ route('groups.cover.medium', $group) }}" />
     @else
         <img class="object-cover w-full h-48 rounded rounded-b-none" src="/images/group.svg" />
     @endif


     <div class="p-4 flex flex-col justify-between h-full">
         <h2 class="text-xl text-gray-800">
             {{ $group->name }}
             @if($group->isOpen())
                 <i class="text-xs text-gray-500 fa fa-globe" title="{{ trans('group.open') }}"></i>
             @elseif($group->isClosed())
                 <i class="text-xs text-gray-500 fa fa-lock" title="{{ trans('group.closed') }}"></i>
             @else
                 <i class="text-xs text-gray-500 fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
             @endif
         </h2>
         <div class="text-gray-600 mt-1 text-sm flex-grow">
             {{ summary($group->body) }}
         </div>


         <div class="text-xs text-gray-600 my-2 flex align-middle space-x-5">

             <div>
                 <i class="ri-discuss-line mr-1"></i>
                 <span> {{ $group->discussions()->count() }}</span>
             </div>

             <div>
                 <i class="ri-calendar-event-line mr-1"></i>
                 <span>{{ $group->actions()->count() }}</span>
             </div>
             <div>
                 <i class="ri-user-3-line mr-1"></i>
                 <span>{{ $group->users()->count() }}</span>
             </div>

              <div>
                 <i class="ri-lightbulb-line mr-1"></i>
                 <span>{{ $group->updated_at->diffForHumans() }}</span>
             </div>

         </div>

         @if($group->isPinned())
             <div class="badge badge-primary" style="min-width: 2em; margin: 0 2px; font-size: 0.5em;">
                 {{ __('Pinned') }}</div>
         @endif
         @if($group->isArchived())
             <div class="badge badge-muted" style="min-width: 2em; margin: 0 2px; font-size: 0.5em;">
                 {{ __('Archived') }}</div>
         @endif
     </div>

 </a>