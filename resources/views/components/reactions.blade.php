@props(['model'])


{{--@foreach ($model->reactions->groupBy('type') as $type)--}}

<div id="model-{{$model->id}}">

    @foreach ($model->reactions as $reaction)
    {{ $reaction->user->name }} reacted with a {{$reaction->type}}

    <a href="{{route('reaction.store', ['model' => 'comment', 'id' => $model->id, 'reaction'=> $reaction->type])}}">
        <div class="flex">
            <img src="{{asset('/images/reactions/' . $reaction->type . '.png')}}" class="image-cover h-8 w-8" />
            <div>
            </div>

        </div>
    </a>
    @endforeach

    @foreach (setting()->getArray('reactions') as $reaction)
    <a up-target="#model-{{$model->id}}" up-reveal="false" class="bg-gray-300 rounded p-1 text-sm mr-2"
        href="{{route('reaction.store', ['model' => 'comment', 'id' => $model->id, 'reaction'=> $reaction])}}">
        {{$reaction}}
    </a>
    @endforeach

</div>