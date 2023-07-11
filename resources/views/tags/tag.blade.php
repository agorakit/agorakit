  <a class="badge rounded-pill" href="{{ action('TagController@show', $tag) }}" style="background-color: {{ $tag->color }}"
      up-follow>
      {{ $tag->name }}
  </a>
