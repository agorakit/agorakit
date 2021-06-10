@props(['title'])

<div class="">
    @if ($title)
    <h3>{{$title}}</h3>
    @endif
    {{$slot}}
</div>