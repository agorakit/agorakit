 <div class="dropdown">
     <a class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="false">
         <i class="fas fa-ellipsis-h"></i>
     </a>
     <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

         @can('update', $action)
             <a class="dropdown-item" href="{{ route('groups.actions.edit', [$group, $action]) }}">
                 <i class="fa fa-pencil me-2"></i>
                 {{ trans('messages.edit') }}
             </a>
         @endcan

         @can('delete', $action)
             <a class="dropdown-item" href="{{ route('groups.actions.deleteconfirm', [$group, $action]) }}" up-layer="new">
                 <i class="fa fa-trash me-2"></i>
                 {{ trans('messages.delete') }}
             </a>
         @endcan

         @auth
             <a class="dropdown-item" href="{{ route('groups.actions.history', [$group, $action]) }}"><i class="fa fa-history me-2"></i> {{ trans('messages.show_history') }}</a>
         @endauth

     </div>
 </div>
