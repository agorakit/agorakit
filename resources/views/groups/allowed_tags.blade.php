@extends('app')

@section('content')
    <h1 class="mb-3">{{ trans('Manage tags on the content of this group') }}</h1>

    <div class="alert alert-primary">
        {{ trans('If you want to limit the available tags to classify content in this group, enter the tags in the box below. If you want to allow any tag, leave the box empty.') }}
    </div>

    {!! Form::open(['action' => ['GroupTagController@update', $group]]) !!}

    @include('partials.tags_select')

    @if ($tags->count() > 0)
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('For your information, here are all the tags in use in this group') }} :
            @foreach ($tags as $tag)
                {{ $tag->name }},
            @endforeach
        </div>
    @endif

    <div class="form-group mt-4">
        {!! Form::submit(trans('Save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

@endsection
