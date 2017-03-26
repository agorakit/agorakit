@extends('app')

@section('content')

    @include('groups.tabs')

    <div class="tab_content">

        <div class="help">
            <h4>{{trans('membership.howtojoin')}}</h4>
            <p>
                {{trans('membership.howtojointext')}}
            </p>
        </div>
    </div>
    
@endsection
