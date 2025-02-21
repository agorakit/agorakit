<div class="form-group">
    {!! Form::label('location', trans('messages.location') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_privacy_and_help')}}
    </div>
    {!! Form::text('street_address', null, ['class' => 'form-control']) !!}
    {!! Form::label('city', trans('messages.city') . ':') !!}
    {!! Form::text('city', null, ['class' => 'form-control']) !!}
    {!! Form::label('county', trans('messages.county') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.county_help')}}
    </div>
    {!! Form::text('county', null, ['class' => 'form-control']) !!}
    {!! Form::label('country', trans('messages.country') . ':') !!}
    {!! Form::select('country', $country_menu_options, $default_country, ['class' => 'form-control']) !!}
    {!! Form::label('location_name', trans('messages.location_name') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_name_help')}}
    {!! Form::text('location_name', null, ['class' => 'form-control']) !!}
    </div>
</div>
