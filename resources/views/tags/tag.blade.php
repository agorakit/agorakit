<a class="badge" href="{{ action('TagController@show', $tag) }}" style="background-color: {{ $tag->color }}">
    {{ $tag->name }}
</a>
