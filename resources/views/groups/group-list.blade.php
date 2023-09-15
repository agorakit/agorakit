<div  up-expand up-reveal="false" 
class="d-flex items-start py-3 hover:bg-gray-100 border-b border-gray-300">

     @if($group->hasCover())
         <img class="h-12 w-12 rounded object-cover mx-1  flex-shrink-0"
             src="{{ route('groups.cover.medium', $group) }}" />
     @else
         <img class="h-12 w-12 rounded object-cover mx-1  flex-shrink-0" src="/images/group.svg" />
     @endif

    <div class="mx-2 flex-grow -mt-1">

        <div class="text-gray-900 text-sm sm:text-base">
            <a
                href="{{ route('groups.show', $group) }}">
                {{ summary($group->name) }}
            </a>
        </div>


        @if($group->tags->count() > 0)
            <div class="text-secondary text-xs overflow-hidden h-5">
                @foreach($group->tags as $tag)
                    @include('tags.tag')
                @endforeach
            </div>
        @endif

<div class="text-sm text-gray-700">
 {{ summary($group->body, 100) }}
 </div>

        <div class="text-secondary text-xs">
            {{ trans('created at') }}
            {{ $group->created_at->diffForHumans() }} - 
            {{ trans('updated at') }}
            {{ $group->updated_at->diffForHumans() }}
        </div>
    </div>

    



</div>