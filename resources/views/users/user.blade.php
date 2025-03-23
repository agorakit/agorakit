 <div class="col-sm-4 col-lg-3">
     <div class="card h-100" up-expand>
         <div class="card-body p-4 text-center">
             <div class="">
                 <a href="{{ route('users.show', $user) }}">
                     @if (isset($user))
                         <img alt="" class="avatar avatar-xl mb-3 rounded"
                             src="{{ route('users.cover', [$user, 'medium']) }}" title="{{ $user->name }}" />
                     @else
                         <span class="avatar avatar-xl mb-3 rounded" title="Unknown user">?</span>
                     @endif

                 </a>
             </div>

             <h3>
                 {{ $user->name }}
             </h3>

             @auth
                 <div class="text-meta mb-2">
                     {{ trans('messages.last_activity') }} : {{ $user->updated_at->diffForHumans() }}
                 </div>

                 <div class="mb-2">{{ summary($user->body) }}</div>

                 @if ($user->tags->count() > 0)
                     <div class="d-flex flex-wrap gap-1 mb-1">
                         @foreach ($user->tags as $tag)
                             @include('tags.tag')
                         @endforeach
                     </div>
                 @endif

                 <div class="d-flex flex-wrap gap-1 mb-2">
                     @foreach ($user->groups as $group)
                         @unless ($group->isSecret())
                             <a class="tag" href="{{ route('groups.show', [$group]) }}">

                                 @if ($group->isOpen())
                                     <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
                                 @elseif ($group->isClosed())
                                     <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                                 @endif
                                 {{ $group->name }}

                             </a>
                         @endunless
                     @endforeach
                 </div>

             </div>
         @endauth
     </div>
 </div>
