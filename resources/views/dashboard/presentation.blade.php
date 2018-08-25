@extends('app')

@section('content')

    <div class="tab_content">

        <h1>
            {{setting('name')}}
        </h1>

        <div>
            {!! setting('homepage_presentation', trans('documentation.intro')) !!}
        </div>

        <div class="btn-group mt-5 d-flex justify-content-center">
            <a class="btn btn-outline-primary" href="{{ url('login') }}">{{ trans('messages.login') }}</a>
            <a class="btn btn-outline-secondary" href="{{ url('register') }}">{{ trans('messages.register') }}</a>
        </div>

        {{--
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
        @foreach (App\Group::whereNotNull('cover')->inRandomOrder()->limit(10)->get() as $group)
        <div class="item  @if ($loop->first) active @endif">
        <img src="{{route('groups.cover.large', $group)}}" class="img-fluid">
        <div class="carousel-caption">
        <h3>{{$group->name}}</h3>
        <p>{{summary($group->body)}}</p>
    </div>
</div>
@endforeach
</div>

<!-- Controls -->
<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
<span class="sr-only">Previous</span>
</a>
<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
<span class="sr-only">Next</span>
</a>
</div>


<h1>
{{App\User::count()}} personnes ont lancé {{App\Discussion::count()}} discussions
et ont téléchargé {{App\File::count()}} fichiers.
</h1>
--}}


</div>

@endsection
