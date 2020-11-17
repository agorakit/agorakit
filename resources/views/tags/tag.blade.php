  <a up-follow class="text-xs text-gray-700 px-1 hover:bg-gray-400 rounded-sm" href="{{ action('TagController@show', $tag) }}">
    <span class="inline-block w-2 h-2 rounded-sm" style="background-color: {{$tag->color}}"></span>
    <span class="hover:text-gray-900">{{$tag->name}}</span>
  </a>
