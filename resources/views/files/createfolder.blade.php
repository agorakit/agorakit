@extends('dialog')

@section('content')



<h1>{{ trans('messages.create_folder') }}</h1>

<p>{{ trans('messages.create_folder_help') }}</p>


{!! Form::open(['url' => route('groups.files.createfolder', ['group' => $group, 'parent' => $parent])]) !!}


<div class="form-group">
    <label for="name">{{ trans('messages.name') }}</label>
    <input class="form-control" name="name" type="text" value="{{ old('name') }}" />

</div>



<div class="flex justify-between items-center my-8">
    <input class="btn btn-primary" type="submit" value="{{ trans('messages.create') }}" />
    <a href="#" class="js-back">{{ trans('messages.cancel') }}</a>
</div>

{!! Form::close() !!}




@endsection