@extends('app')

@section('content')

    <div class="page_header">
        <h1><a href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i> {{ trans('messages.all_groups') }}</h1>
    </div>

    @include('dashboard.tabs')

    <div class="tab_content">

        {{--
        @push ('js')

        <script>
        $(document).ready(function(){
        @foreach ($all_tags as $tag)
        $("#toggle-tag-{{$tag->tag_id}}").click(function(){
        $(".tag-group").hide();
        $(".tag-{{$tag->tag_id}}").show();
        $(".tag-toggle").removeClass('active');
        $(this).addClass('active');

    });
@endforeach

$("#toggle-tag-all").click(function(){
$(".tag-group").show();
$(".tag-toggle").removeClass('active');
$(this).addClass('active');
});
});

</script>
@endpush




@foreach ($all_tags as $tag)
<a class="btn btn-primary btn-sm tag-toggle" id="toggle-tag-{{$tag->tag_id}}">{{$tag->name}}</a>
@endforeach

<a class="btn btn-primary btn-sm tag-toggle active" id="toggle-tag-all">{{trans('messages.show_all')}}</a>
--}}


<div class="tab_content">
    @if ($groups)

        <div class="row">
            @forelse( $groups as $group )
                <div class="col-md-4">
                    <div class="card tag-group @foreach ($group->tags as $tag)tag-{{$tag->tag_id}} @endforeach">
                        <img class="card-img-top" src="{{ route('groups.cover', $group)}}" />

                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $group->name }}
                                @if ($group->isPublic())
                                    <i class="fa fa-globe" title="{{trans('group.open')}}"></i>
                                @else
                                    <i class="fa fa-lock" title="{{trans('group.closed')}}"></i>
                                @endif
                            </h5>
                            <p class="card-text">
                                {{summary($group->body) }}
                                @foreach ($group->tags as $tag)
                                    <span class="label label-default">{{$tag->name}}</span>
                                @endforeach
                            </p>
                            <a class="btn btn-primary" href="{{ action('GroupController@show', $group) }}"></i>
                                {{ trans('messages.visit') }}
                            </a>

                            @unless ($group->isMember())
                                @can ('join', $group)
                                    <a class="btn btn-primary" href="{{ action('MembershipController@store', $group->id) }}">
                                        {{ trans('group.join') }}
                                    </a>
                                @endcan
                            @endunless

                        </div>
                    </div>
                </div>



                @if ($loop->iteration % 3 == 0)
                </div>
                <div class="row">
                @endif


            @empty
                {{trans('messages.nothing_yet')}}
            @endforelse

        </div>
    @else
        {{trans('messages.nothing_yet')}}
    @endif
</div>





</div>

@endsection
