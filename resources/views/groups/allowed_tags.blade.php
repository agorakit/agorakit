@extends('group')

@section('content')
    <h1 class="mb-3">{{ trans('group.allowed_tags_title') }}</h1>

    <div class="alert alert-primary">
        {{ trans('group.allowed_tags_help') }}
    </div>

    {!! Form::open(['action' => ['GroupTagController@update', $group]]) !!}

    @include('partials.tags_select')

    @if ($tags->count() > 0)
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('messages.group_tags_help') }} :
            @foreach ($tags as $tag)
                {{ $tag->name }},
            @endforeach
        </div>
    @endif

    <div class="form-group mt-4">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

@endsection
