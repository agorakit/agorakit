    {!! Form::label('location[name]', trans('Location Name') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_name_help')}}
    {!! Form::text('location[name]', $group->location_data['name'], ['class' => 'form-control']) !!}
    </div>
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.location_privacy_and_help')}}
    </div>
    {!! Form::label('location[street]', trans('Street Address') . ':') !!}
    {!! Form::text('location[street]', $group->location_data['street'], ['class' => 'form-control', 'size' => '100']) !!}
    {!! Form::label('location[city]', trans('City') . ':') !!}
    {!! Form::text('location[city]', $group->location_data['city'], ['class' => 'form-control', 'size' => '30']) !!}
    {!! Form::label('location[county]', trans('County') . ':') !!}
    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.county_help')}}
    </div>
    {!! Form::text('location[county]', $group->location_data['county'], ['class' => 'form-control', 'size' => '30']) !!}
    {!! Form::label('location[country]', trans('Country') . ':') !!}
    {!! Form::text('location[country]', $group->location_data['country'], ['class' => 'form-control', 'size' => '30']) !!}
