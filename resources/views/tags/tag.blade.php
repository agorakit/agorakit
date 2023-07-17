  <a class="badge" href="{{ action('TagController@show', $tag) }}" style="background-color: {{ $tag->color }}"
      up-follow>
      {{ $tag->name }}
  </a>
