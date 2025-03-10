<?php

namespace App\Traits;

use Illuminate\Http\Request;

/**
 * This trait allows any model to have a location (ie geographical address)
 * Attributes from the web form:
 * - location[name]
 * - location[street]
 * - location[city]
 * - location[county]
 * - location[country]
 */
trait HasLocation
{
    private $allowed_location_keys = ["name", "street", "city", "county", "country"];

    /**
     * Get location data from database `location` field
     */
    public function getLocationData()
    {
        // Default value
        $location_data = [];
        // Decoding the JSON field
        if (!$location_data = json_decode($this->location, true)) {
            // This is probably an old `location` field, so we convert and put everything in street
            $location_data['street'] = $this->location;
        }
        array_intersect_key($this->allowed_location_keys, $location_data); // first time I use that one :)
        return $location_data;
    }


    /**
     * Sets the model location from the location found in request
     * Does not save the model to DB
     */
    public function setLocationFromRequest(Request $request): bool
    {
        if ($request->has('location')) {
            $location = $request->get('location');
            array_intersect_key($this->allowed_location_keys, $location);
            return $this->location = json_encode($location, JSON_UNESCAPED_UNICODE);
        }

        return false;
    }


    /**
     * Returns an array of latitude longitude if one is found, null otherwise
     */
    public function getGeolocation(): array|bool
    {
        if ($this->longitude <> 0 && $this->latitude <> 0) {
            $geolocation['latitude'] = $this->latitude;
            $geolocation['longitude'] = $this->longitude;
            return $location;
        }
        return false;
    }


    /**
     * Geocode the model using $this->getLocationData() data and sets $this->latitude and $this->longitude
     */
    function geocode()
    {
        $geolines = [];
        $location_data = $this->getLocationData();
        foreach ($location_data as $key => $val) {
            if ($key == 'name') {
            } else if ($key == 'county' && array_key_exists('country', $location_data)) {
                $geolines[] = $this->parse_county($val, $location_data['country']);
            } else {
                $geolines[] = $val;
            }
        }
        // Calling geocode function - even more abstracted than geocoder php.
        // Pass it a string and it will return an array with longitude and latitude or false in case of problem
        $result = app('geocoder')->geocode(implode(",", $geolines))->get()->first();
        if ($result) {
            $this->latitude = $result->getCoordinates()->getLatitude();
            $this->longitude = $result->getCoordinates()->getLongitude();
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
    public function location_display($format = "short")
    {
        $location_data = $this->getLocationData();
        $parts = [];
        foreach ($this->allowed_location_keys as $key) {
            if (array_key_exists($key, $location_data) && $location_data[$key]) {
                if ($format == "short" && $key == 'street') {
                    $parts[] = substr($location_data[$key], 0, 30);
                } else {
                    $parts[] = $location_data[$key];
                }
            }
        }
        return implode(", ", $parts);
    }
}
