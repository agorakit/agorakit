{{$model->link()}}
<pre>
{{$model->reactions->toArray()}}
</pre>
@foreach ($model->reactions as $reaction)

<li>{{ $reaction->user->name }} reacted with a {{$reaction->type}}

    {{--

<a href="{{route('reaction.store', ['model' => 'comment', 'id' => $model->id, 'reaction'=> $reaction])}}">
    <div class="flex">


        <img src="{{asset('/images/reactions/' . $reaction . '.png')}}" class="image-cover h-8 w-8" />

        <div>

        </div>

    </div>
    </a>

    --}}

    @endforeach