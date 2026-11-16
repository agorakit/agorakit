@extends('app')

@section('content')
    <h1>{{ trans('Create a new group') }}</h1>

    @if (setting('user_can_import_groups') == 1 || Auth::user()->isAdmin())
            <a class="btn btn-primary" href="{{ route('groups.import') }}"
                up-layer="new">
                <i class="fa fa-file me-2"></i>
                {{ trans('Import from a File') }}
            </a>
    @endif

    {!! Form::open(['action' => ['GroupController@store'], 'files' => true], null) !!}

    @include('groups.form')

    <div class="form-group">
        {!! Form::submit(trans('Create the group'), ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}
@endsection
