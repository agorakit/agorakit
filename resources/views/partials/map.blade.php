<style>
    #mapid {
        height: 600px;
    }

    .marker {
        width: 1rem;
        height: 1rem;
        display: block;
        position: relative;
        border-radius: 50%;
        border: 2px solid gray;
        box-shadow: 0 2px 3px 0 rgba(0, 0, 0, 0.5);
    }
</style>

<div class="mb-4 js-map" data-json-url="{{ $json }}" id="mapid"></div>

<span class="badge" style="background-color: #8dc91e;">Group</span>

<span class="badge" style="background-color: #1e60c9">User</span>

<span class="badge" style="background-color: #871ec9">Action</span>
