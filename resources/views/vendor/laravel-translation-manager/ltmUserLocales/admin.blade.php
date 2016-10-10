@extends('layouts.master-fx')

@section('content')
    <div>
        @if($op === 'create')
            {!! \Form::open(['route' => 'userlocales.store']) !!}
        @elseif($op === 'edit')
            {!! \Form::model($userlocale, ['method' => 'PATCH', 'route' => ['userlocales.update', $userlocale->id]]) !!}
        @else
            {!! \Form::model($userlocale, [$userlocale->id]) !!}
        @endif
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="user_id">@lang('ltm-user-locales.user'):</label>
                {!! Form::select('user_id', [0 => ''], $users,  Input::old('user_id'), [isViewOp($op) ? 'disabled' : '','class' => 'form-control', ]) !!}
                {!! Form::text('user', $userlocale ? $userlocale->user->id : '', [isViewOp($op) ? 'readonly' : 'data-vsch_completion'=>'users:id;id:user_id','class' => 'form-control', ]) !!}
                {!! Form::hidden('user_id', Input::old('user_id'), ['id'=>'user_id']) !!}
            </div>
        </div>
        @if($op === 'create' || $op === 'edit')
            <div class='form-group col-sm-1'>
                <label>&nbsp;</label>
                <br>
                <a href="@route('users.create')" @linkAsButton('warning')>@lang('messages.create')</a>
            </div>
        @endif
        <div class="row">
            <div class="form-group col-sm-3">
                <label for="locale">@lang('ltm-user-locales.locale'):</label>
                {!! Form::text('locale', Input::old('locale'), [isViewOp($op) ? 'readonly' : '','class'=>'form-control', 'placeholder'=>noEditTrans('ltm-user-locales.locale'), ]) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <a href="{!! getReturnUrl() !!}" @linkAsButton('default')>@lang('messages.cancel')</a>
                @if($op === 'create' || $op === 'edit')
                    {!! formSubmit(trans('messages.save'), ['class' => 'btn btn-sm btn-'.$panelType])!!}
                @elseif($op === 'delete')
                    {!! formSubmit(trans('messages.delete'), ['form' => 'userlocales-delete', 'class' => 'btn btn-sm btn-danger']) !!}
                @endif

                @if($op !== 'create' && appDebug())
                    <a href="{!! \URL::route('userlocales.remote', ['id'=> $userlocale->id])!!}" @linkAsButton('danger')>@lang('messages.remote-sync')</a>
                @endif
            </div>
        </div>
        {!! \Form::close() !!}

        @if($op === 'delete')
            {!! \Form::open(['style' => 'display: inline-block;', 'id' =>'userlocales-delete', 'method' => 'DELETE', 'route' => ['userlocales.destroy', $userlocale->id]]) !!}
            {!! \Form::close() !!}
        @endif
    </div>
@stop
