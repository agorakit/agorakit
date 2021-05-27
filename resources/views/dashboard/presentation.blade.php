@extends('app')

@section('content')

<div class="tab_content">

    <h1>
        {{setting('name')}}
    </h1>

    <div class="mb-3">
        @if (setting()->localized()->get('homepage_presentation'))
        {!! setting()->localized()->get('homepage_presentation') !!}
        @else
        {!! setting()->get('homepage_presentation', trans('documentation.intro'))!!}
        @endif
    </div>


    @if($groups)
    <div class="groups">
        <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 py-4">
            @foreach($groups as $group)
            @include('groups.group')
            @endforeach
        </div>
        {!! $groups->links() !!}

    </div>
    @endif


</div>

@endsection