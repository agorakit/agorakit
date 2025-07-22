@extends('app')

@section('content')
    <h1>{{ trans('group.create_group_title') }}</h1>

    @if (setting('user_can_import_groups') == 1 || Auth::user()->isAdmin())
            <a class="btn btn-primary" href="{{ route('groups.import') }}"
                up-layer="new">
                <i class="fa fa-file me-2"></i>
                {{ trans('group.import_group_button') }}
            </a>
    @endif

    {!! Form::open(['action' => ['GroupController@store'], 'files' => true], null) !!}

    @include('groups.form')

    <div class="form-group">
        {!! Form::submit(trans('group.create_button'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
