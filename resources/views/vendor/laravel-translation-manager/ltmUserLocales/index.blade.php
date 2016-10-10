@extends('layouts.master-fx')
{{-- @formatter:off --}}
@section('wrapper-page-container-top')
    <div class="container-fluid">
@overwrite

@section('wrapper-page-container-bottom')
    </div>
@overwrite
{{-- @formatter:on --}}

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="pagination btn-group-xs">
                <a href="/admin" role="button" class="btn btn-sm btn-default">@lang('messages.admin-back')</a>
                <a href="{!! \URL::route('userlocales.create') !!}" role="button" class="btn btn-sm btn-{{ $panelType }}">@lang('messages.create')</a>
                @if(appDebug())
                    <a href="{!! \URL::route('userlocales.remoteall')!!}" role="button" class="btn btn-danger btn-sm">@lang('messages.remote-sync-all')</a>
                @endif
            </div>
            {{--<a href="{!!$prev!!}" role="button" class="btn btn-default btn-sm{{empty($prev) ? ' disabled' : ''}}">@lang('pagination.previous')</a>--}}
            {{--<a href="{!!$next!!}" role="button" class="btn btn-default btn-sm{{empty($next) ? ' disabled' : ''}}">@lang('pagination.next')</a>--}}
            {!! $userlocales->appends($filterParams)->render() !!}
        </div>
    </div>
    {!! \Form::open(['route' => ['userlocales.index'], 'method' => 'get', 'id'=>"filter-userlocales", 'class'=>"form-horizontal"]) !!}
    <input name="_page" type="hidden" value="{{\Input::get('_page', '') }}">
    {!! \Form::close() !!}
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-striped item-index table-condensed">
                <thead>
                    <tr>
                        <th class="col-xs-3">&nbsp;</th>
                        <th>@lang('ltm-user-locales.user_id')</th>
                        <th>@lang('ltm-user-locales.locale')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="form-group-xs">
                        <td>
                            <a href="{!! \URL::route('userlocales.index', \Input::only('page')) !!}" role="button" class="btn btn-default btn-xs">@lang('messages.reset')</a>
                            {!! formSubmit(trans('messages.apply'), ['form' => 'filter-userlocales', 'class' => 'btn btn-xs btn-success']) !!}
                        </td>
                        <td>{!! Form::text('user', Input::get('user'), ['form' => 'filter-userlocales', 'data-vsch_completion'=>'users:id;id:user_id','class'=>'form-control', 'placeholder'=>noEditTrans('ltm-user-locales.user_id'), ]) !!}
                            {!! Form::hidden('user_id', Input::old('user_id'), ['form' => 'filter-userlocales', 'id'=>'user_id']) !!}</td>
                        <td>{!! Form::text('locale', Input::get('locale'), ['form' => 'filter-userlocales', 'class'=>'form-control', 'placeholder'=>noEditTrans('ltm-user-locales.locale'), ]) !!}</td>
                    </tr>
                    @foreach ($userlocales as $userlocale)
                        <tr>
                            <td>
                                <a href="{!! \URL::route('userlocales.delete', ['id'=>$userlocale->id])!!}" role="button" class="btn btn-danger btn-xs">@lang('messages.delete')</a>
                                <a href="{!! \URL::route('userlocales.edit', ['id'=>$userlocale->id])!!}" role="button" class="btn btn-warning btn-xs">@lang('messages.edit')</a>
                                <a href="{!! \URL::route('userlocales.show', ['id'=>$userlocale->id])!!}" role="button" class="btn btn-info btn-xs">@lang('messages.view')</a>
                            </td>
                            <td>{{ $userlocale->user_id . ':' . $userlocale->user->id }}</td>
                            <td>{{ $userlocale->locale }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
