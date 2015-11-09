@extends('app')

@section('content')

@include('partials.grouptab')

<div class="tab_content">




  <h2>{{trans('group.history_of_this_group')}}</h2>


  @foreach($group->revisionHistory as $history )
  <p class="history">
  {{ $history->created_at->diffForHumans() }}, {{ $history->userResponsible()->name }} {{trans('messages.changed')}} <strong>{{ $history->fieldName() }}</strong> {{trans('messages.changed_from')}} <code>{{ $history->oldValue() }}</code> {{trans('messages.changed_to')}} <code>{{ $history->newValue() }}</code>
</p>
  @endforeach

</div>



@endsection
