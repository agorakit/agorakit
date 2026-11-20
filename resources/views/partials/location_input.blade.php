@if (is_a($model, "App\CalendarEvent") && $listedLocations)
    <fieldset class="form-fieldset">
        {!! Form::label('listed_location', t('Location')) !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ t('Will this event take place in one of these locations?') }}
        </div>
        {!! Form::select('listed_location', [''=> ' --- '] + $listedLocations + ['other' => t('Other')],
	    $listedLocation,
	    ['id' => 'location_menu', 'class' => 'form-control mb-4', 'up-switch' => '.js-new-location'])
	!!}
    <p class="js-new-location" up-show-for="other"><strong>{{ t('Otherwise please enter your venue here:') }}</strong></p>
    </fieldset>
@endif

    @if (is_a($model, "App\CalendarEvent") && $listedLocations)<fieldset class="form-fieldset js-new-location" up-show-for="other">
      @else <fieldset class="form-fieldset">@endif
      <label class="form-label h3">{{t('Location')}}</label>
        <div class="small-help mb-3">
            <i class="fas fa-info-circle"></i>
            {{ t('Only fill in the information that you are willing to put online. The address information, even partial, allows us to display a map with the location of people, groups and events. If you do not want to be shown on a map do not put anything here. You can also just put your town (or your street name without the house number) to appear on the map, but not in a precise manner.') }}
        </div>
        {!! Form::label('location[street]', t('Street Address') . ':') !!}
        {!! Form::text('location[street]', $model->location->street, ['class' => 'form-control mb-3', 'size' => '100']) !!}
        {!! Form::label('location[city]', t('City') . ':') !!}
        {!! Form::text('location[city]', $model->location->city, ['class' => 'form-control mb-3', 'size' => '30']) !!}
        {!! Form::label('location[county]', t('County') . ':') !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ t('Filling this field might help the geocoder to find the correct location.') }}
        </div>
        {!! Form::text('location[county]', $model->location->county, ['class' => 'form-control mb-3', 'size' => '30']) !!}
        {!! Form::label('location[country]', t('Country') . ':') !!}
        {!! Form::text('location[country]', $model->location->country, ['class' => 'form-control mb-3', 'size' => '30']) !!}
        {!! Form::label('location[name]', t('Location Name') . ':') !!}
        <div class="small-help">
            <i class="fas fa-info-circle"></i>
            {{ t('Enter a name for this location for future re-use.') }}
            {!! Form::text('location[name]', $model->location->name, ['class' => 'form-control']) !!}
        </div>
    </fieldset>
