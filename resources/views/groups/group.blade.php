 <a up-follow href="{{ action('GroupController@show', $group) }}"
     class="relative rounded-md border-gray-300 border shadow-md hover:shadow-xl flex flex-col justify-start @if ($group->isArchived()) status-archived @endif">

     @auth
         @if(Auth::user()->isMemberOf($group))
             <div
                 class="absolute top-0 right-0 py-1 px-2 bg-gray-700 text-gray-200 capitalize rounded-full m-2 text-xs shadow">
                 {{ __('membership.member') }}
             </div>
         @endif
     @endauth

     @if($group->isPinned())
         <div class="absolute top-0 left-0 py-1 px-2 bg-gray-700 text-gray-200 capitalize rounded-full m-2 text-xs shadow"
             title="{{ trans('group.pinned') }}">
             <i class="far fa-star"></i>
         </div>
     @endif

     @if($group->isArchived())
         <div class="absolute top-0 left-0 py-1 px-2 bg-gray-700 text-gray-200 capitalize rounded-full m-2 text-xs shadow"
             title="{{ trans('group.archived') }}">
             <i class="fas fa-archive"></i>
         </div>
     @endif

     @if($group->hasCover())
         <img class="object-cover w-full h-40 rounded rounded-b-none"
             src="{{ route('groups.cover.medium', $group) }}" />
     @else
         <img class="object-cover w-full h-40 rounded rounded-b-none" src="/images/group.svg" />
     @endif


     <div class="p-4 flex flex-col justify-between h-full">
         <h2 class="text-xl text-gray-800">
             {{ $group->name }}
             @if($group->isOpen())
                 <i class="text-xs text-gray-500 fa fa-globe" title="{{ trans('group.open') }}"></i>
             @elseif($group->isClosed())
                 <i class="text-xs text-gray-500 fa fa-lock" title="{{ trans('group.closed') }}"></i>
             @else
                 <i class="text-xs text-gray-500 fa fa-eye-slash"
                     title="{{ trans('group.secret') }}"></i>
             @endif
         </h2>
         <div class="text-gray-700 mt-1 text-sm sm:text-xs flex-grow">
             {{ summary($group->body) }}
         </div>


         <div class="text-xs text-gray-700 my-2 flex align-middle space-x-5">

             <div>
                 <i class="far fa-comments mr-1"></i>
                 <span> {{ $group->discussions()->count() }}</span>
             </div>

             <div>
                 <i class="far fa-calendar-alt mr-1"></i>
                 <span>{{ $group->actions()->count() }}</span>
             </div>
             <div>
                 <i class="fas fa-users mr-1"></i>
                 <span>{{ $group->users()->count() }}</span>
             </div>

             <div>
                 <i class="far fa-lightbulb mr-1"></i>
                 <span>{{ $group->updated_at->diffForHumans() }}</span>
             </div>

         </div>
     </div>

 </a>