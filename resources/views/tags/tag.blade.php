<a class="tag" href="{{ action('TagController@show', $tag) }}">
<span class="circle" style="background-color: {{ $tag->color }}"></span>
    {{ $tag->name }}
</a>
