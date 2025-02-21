<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

/**
 * This trait allows any model to have a location (ie geographical address)
 * Storage :
 * - users in users/[id]/location
 * - groups is groups/[id]/location
 * - actions in groups/[id]/actions/[id]/location
 *
 * Attributes from the web form:
 * - location_name
 * - street_address
 * - city
 * - county
 * - country
 */
trait HasLocation
{
    // list of attribute names (for validation ?? FIXME)
    private $location_attributes = ['location_name', 'street_address', 'city', 'county', 'country'];

    public function getCountryMenuOptions()
    {
        $path = config('translation.country_menu_options_path') . "/" . config('app.locale');
        $file = $path . "/" . "countries.json";
        $options = File::json(base_path($file));
        return $options;
    }

    /**
     * Returns whether a geocode has been stored for this model
     */
    public function hasGeolocation()
    {
        return Storage::exists($this->getLocationPath()); // FIXME
    }

    public function getGeolocation(): string
    {
        if (!$this->hasGeolocation()) {
          return null;
        }
        return ['latitude' => $this->{'latitude'}, 'longitude' => $this->{'longitude'}];
    }

    /**
     * Save the location for this model from the request.
     */
    public function setLocationFromRequest(Request $request)
    {
        $location = [];
        $geoline = [];
        foreach ($location_attributes as $attr) {
          if ($request->has($attr)) {
            $value = $request->string($attr)->trim(); // FIXME validation ?
            $location[$attr] = $value;
            if ($attr == 'location_name') {}
            else if ($attr == 'county') {
              $geoline[] = parse_county($value, $country);
            }
            else {
              $geoline[] = $value;
            }
          }
        }
        $this->{'location'} = $location->toJson();
        // Calling geocode function - even more abstracted than geocoder php.
        // Pass it a string and it will return an array with longitude and latitude or false in case of problem
        $geocode = app('geocoder')->geocode(implode(",", $geoline))->get()->first();
        if ($geocode) {
          $this->{'latitude'} = $geocode->getCoordinates()->getLatitude();
          $this->{'longitude'} = $geocode->getCoordinates()->getLongitude();
        }
        return $this;
    }

  /**
   * Parse `county` input from the request, for some specific cases.
   * At the moment: French departement codes only.
   */
  function parse_county($county, $country_code)
  {
     if (!is_numeric($county)) {
       return $county;
     }
     if ($country_code <> 'FR') {
       return $county;
     }
     if (str_len($county) < 4) { // French departement 2 or 3-digits code
       return "FR-" . $county;  // ISO 3166-2
     }
     return $county;
  }

}
