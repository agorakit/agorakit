@if (is_a($model, "App\CalendarEvent") && $listedLocations)
    <fieldset class="form-fieldset">
        {!! Form::label('listed_location', trans('Location')) !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('Will this event take place in one of these locations?') }}
        </div>
        {!! Form::select('listed_location', [''=> ' --- '] + $listedLocations + ['other' => trans('Other')],
	    $listedLocation,
	    ['id' => 'location_menu', 'class' => 'form-control mb-4', 'up-switch' => '.js-new-location'])
	!!}
    <p class="js-new-location" up-show-for="other"><strong>{{ trans('Otherwise please enter your venue here:') }}</strong></p>
    </fieldset>
@endif

    @if (is_a($model, "App\CalendarEvent") && $listedLocations)<fieldset class="form-fieldset js-new-location" up-show-for="other">
      @else <fieldset class="form-fieldset">@endif
      <label class="form-label h3">{{trans('Location')}}</label>
        <div class="small-help mb-3">
            <i class="fas fa-info-circle"></i>
            {{ trans('Only fill in the information that you are willing to put online. The address information, even partial, allows us to display a map with the location of people, groups and events. If you do not want to be shown on a map do not put anything here. You can also just put your town (or your street name without the house number) to appear on the map, but not in a precise manner.') }}
        </div>
        {!! Form::label('location[street]', trans('Street Address') . ':') !!}
        {!! Form::text('location[street]', $model->location->street, ['class' => 'form-control mb-3', 'size' => '100']) !!}
        {!! Form::label('location[city]', trans('City') . ':') !!}
        {!! Form::text('location[city]', $model->location->city, ['class' => 'form-control mb-3', 'size' => '30']) !!}
        {!! Form::label('location[county]', trans('County') . ':') !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('Filling this field might help the geocoder to find the correct location.') }}
        </div>
        {!! Form::text('location[county]', $model->location->county, ['class' => 'form-control mb-3', 'size' => '30']) !!}
        {!! Form::label('location[country]', trans('Country') . ':') !!}
        {!! Form::text('location[country]', $model->location->country, ['class' => 'form-control mb-3', 'size' => '30']) !!}
        {!! Form::label('location[name]', trans('Location Name') . ':') !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ trans('Enter a name for this location for future re-use.') }}
            {!! Form::text('location[name]', $model->location->name, ['class' => 'form-control']) !!}
        </div>
    </fieldset>
