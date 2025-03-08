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
 * - location[name]
 * - location[street]
 * - location[city]
 * - location[county]
 * - location[country]
 */
trait HasLocation
{
    private $location_specs = ["name", "street", "city", "county", "country"];

    /**
     * Get location data from database `location` field
     */
    public function getLocationData()
    {
        if(!$this->location) $this->location = "";
        $location_data = json_decode($this->location, true);
        if (!$location_data) {
          $location_data = [];
        }
        foreach($this->location_specs as $key) {
          if (!array_key_exists($key, $location_data)) {
            $location_data[$key] = null;
            }
        }
        $this->location_data = $location_data;
    }

    /**
     * Returns whether a geocode has been stored for this model
     */
    public function hasGeolocation()
    {
        return ($this->longitude <> 0 && $this->latitude <> 0);
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
        if (is_string($location_data)) {
	    $location_data = json_decode($location_data, true);
	}
        if (!$location_data) {
            $this->latitude = 0;
            $this->longitude = 0;
            return true;
        }
        $geoline = [];
        foreach ($location_data as $key => $val) {
            if ($key == 'name') {}
            else if ($key == 'county' && array_key_exists('country', $location_data)) {
              $geoline[] = $this->parse_county($val, $location_data['country']);
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

    /**
     * We need a function to display a location as a string.
     * Knowing that it is stored as a JSON structure in the database,
     * with keys: name, street, city, county, country.
     */
    public function location_display($format="short")
    {
        $this->getLocationData();
        $parts = [];
        foreach($this->location_specs as $key) {
           if (array_key_exists($key, $this->location_data) && $this->location_data[$key]) {
            if ($format == "short" && $key == 'street') {
              $parts[] = substr($this->location_data[$key], 0, 30);
              }
            else {
              $parts[] = $this->location_data[$key];
            }
          }
        }
        return implode(", ", $parts);
    }

}
