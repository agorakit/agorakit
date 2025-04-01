<a class="tag" href="{{ action('LocationController@show', $location) }}">
<span class="circle" style="background-color: {{ $location->color }}"></span>
    {{ $location->name }}
</a>
