@extends('group')

@section('content')
    <h1 class="mb-3">{{ trans('group.listed_locations_title') }}</h1>

    <div class="alert alert-primary">
        {{ trans('group.listed_locations_help') }}
    </div>

    {!! Form::open(['action' => ['GroupLocationController@update', $group]]) !!}

    @if ($listed_locations)
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('messages.group_locations_help') }} :
            @foreach ($listed_locations as $location)
                <label class="form-check form-switch" for="{{ $location->name }}">
                  <input checked=checked class="form-check-input" id="" name="{{ $location->name }}" type="checkbox">
             <span>{{ $location->name }} ( {{ $location->city }})</span>
    </label>
            @endforeach
        </div>
    @endif

    <div class="form-group mt-4">
        {!! Form::submit(trans('messages.save'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

@endsection
