<?php

namespace App\Traits;

use App\Casts\LocationFromJson;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

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
    private $location_keys = ["name", "street", "city", "county", "country"];

    /**
     * Cast location from database JSON field
     */
    protected function casts(): array
    {
        return ['location' => LocationFromJson::class];
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

    function geocode()
    {
        if (!$this->location) {
            $this->latitude = 0;
            $this->longitude = 0;
            return true;
        }
        $geoline = [];
        foreach (get_object_vars($this->location) as $key => $val) {
            if ($key == 'name') {}
            else if ($key == 'county' && $this->location->country) {
              $geoline[] = $this->parse_county($val, $this->location->country);
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
    function parse_county($county, $country)
    {
     if (!is_numeric($county)) {
       return $county;
     }
     if (strtolower($country) <> 'fr' && strtolower($country) <> "france") {
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
        $parts = [];
        foreach($this->location_specs as $attr) {
           if ($this->location->$attr) {
            if ($format == "short" && $key == 'street') {
              $parts[] = substr($this->location->$attr, 0, 30);
              }
            else {
              $parts[] = $this->location->$attr;
            }
          }
        }
        return implode(", ", $parts);
    }

}
