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

    function geocode($location_data)
    {
        if (!$location_data) {
            $this->latitude = 0;
            $this->longitude = 0;
            return true;
        }
        $geoline = [];
        foreach ($this->location_data as $key => $val) {
            if ($key == 'name') {}
            else if ($key == 'county' && array_key_exists('country', $location)) {
              $geoline[] = parse_county($val, $location['country']);
            }
            else {
              $geoline[] = $val;
            }
        }
        // Calling geocode function - even more abstracted than geocoder php.
        // Pass it a string and it will return an array with longitude and latitude or false in case of problem
        $result = app('geocoder')->geocode(implode(",", $geoline))->get()->first();
        if ($result) {
            $this->{'latitude'} = $result->getCoordinates()->getLatitude();
            $this->{'longitude'} = $result->getCoordinates()->getLongitude();
            return true;
        }
        return false;
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
