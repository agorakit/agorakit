    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_privacy_and_help')}}
    </div>
    {!! Form::label('location[street]', trans('Street Address') . ':') !!}
    {!! Form::text('location[street]', $model->location->street, ['class' => 'form-control', 'size' => '100']) !!}
    {!! Form::label('location[city]', trans('City') . ':') !!}
    {!! Form::text('location[city]', $model->location->city, ['class' => 'form-control', 'size' => '30']) !!}
    {!! Form::label('location[county]', trans('County') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.county_help')}}
    </div>
    {!! Form::text('location[county]', $model->location->county, ['class' => 'form-control', 'size' => '30']) !!}
    {!! Form::label('location[country]', trans('Country') . ':') !!}
    {!! Form::text('location[country]', $model->location->country, ['class' => 'form-control', 'size' => '30']) !!}
    {!! Form::label('location[name]', trans('Location Name') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_name_help')}}
    {!! Form::text('location[name]', $model->location->name, ['class' => 'form-control']) !!}
    </div>
