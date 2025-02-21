<div class="form-group">
    {!! Form::label('location[street]', _('Location') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_privacy_and_help')}}
    </div>
    {!! Form::text('location[street]', null, ['class' => 'form-control']) !!}
    {!! Form::label('location[city]', _('City') . ':') !!}
    {!! Form::text('location[city]', null, ['class' => 'form-control']) !!}
    {!! Form::label('location[county]', _('County') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.county_help')}}
    </div>
    {!! Form::text('location[county]', null, ['class' => 'form-control']) !!}
    {!! Form::label('location[country]', _('Country') . ':') !!}
    {!! Form::text('location[country]', null, ['class' => 'form-control']) !!}
    {!! Form::label('location[name]', _('Location Name') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_name_help')}}
    {!! Form::text('location[name]', null, ['class' => 'form-control']) !!}
    </div>
</div>
