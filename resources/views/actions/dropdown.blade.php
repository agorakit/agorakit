 <div class="dropdown" up-layer="root">
     <a aria-expanded="false" aria-haspopup="true" class="btn" data-bs-toggle="dropdown" id="dropdownMenuButton"
         type="button">
         <i class="fas fa-ellipsis-h"></i>
     </a>
     <div aria-labelledby="dropdownMenuButton" class="dropdown-menu dropdown-menu-end">

         @can('update', $action)
             <a class="dropdown-item" href="{{ route('groups.actions.edit', [$action->group, $action]) }}" up-layer="root">
                 <i class="fa fa-pencil me-2"></i>
                 {{ trans('messages.edit') }}
             </a>
         @endcan

         @can('create-action', $action->group)
             <a class="dropdown-item"
                 href="{{ route('groups.actions.create', [$action->group]) }}?duplicate={{ $action->id }}"
                 up-layer="root">
                 <i class="far fa-copy me-2"></i>
                 {{ trans('messages.duplicate') }}
             </a>
         @endcan

         @can('delete', $action)
             <a class="dropdown-item" href="{{ route('groups.actions.deleteconfirm', [$action->group, $action]) }}"
                 up-layer="new">
                 <i class="fa fa-trash me-2"></i>
                 {{ trans('messages.delete') }}
             </a>
         @endcan

         @auth
             <a class="dropdown-item" href="{{ route('groups.actions.history', [$action->group, $action]) }}"
                 up-layer="root"><i class="fa fa-history me-2"></i>
                 {{ trans('messages.show_history') }}</a>
         @endauth

     </div>
 </div>
