@extends('app')

@section('content')


    <div class="toolbox d-md-flex">
        <div class="d-flex mb-2">
            <h1>
                <a  href="{{ route('index') }}"><i class="fa fa-home"></i></a> <i class="fa fa-angle-right"></i>
                {{ trans('messages.map') }}
            </h1>
        </div>

        <div class="ml-auto">
            @include ('partials.preferences-show')
        </div>
    </div>


    @include('partials.map')





@endsection
