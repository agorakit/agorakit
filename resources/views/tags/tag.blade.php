  <a up-follow style="margin-right: 10px; font-weight: bolder; font-size: 0.8rem; color: #333; text-transform: capitalize" href="{{ action('TagController@show', $tag) }}">
    <span style="display: inline-block; background-color: {{$tag->color}}; width: 10px; height: 10px; border-radius: 50%"></span>
    {{$tag->name}}
  </a>
