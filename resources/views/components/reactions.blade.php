@props(['model'])

<div class="flex items-center">
    <div id="model-{{$model->id}}">

        @foreach ($model->reactions as $reaction)
        @if (Auth::check() && Auth::user()->is($reaction->user))
        <a up-target="#model-{{$model->id}}" up-reveal="false"
            href="{{route('reaction.unreact', ['model' => $model->modelName, 'id' => $model->id])}}">
            <img src="{{asset('/images/reactions/' . $reaction->type . '.png')}}" class="image-cover h-8 w-8 mr-2 p-1"
                title="{{ $reaction->user->name }} - click to remove" />
        </a>
        @else

        <img src="{{asset('/images/reactions/' . $reaction->type . '.png')}}" class="image-cover h-8 w-8 mr-2 p-1"
            title="{{ $reaction->user->name }}" />

        @endif
        @endforeach

    </div>

    @can('react', $model)
    <div class="dropdown">
        <a class="cursor-pointer" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <img src="{{asset('/images/reactions/reaction.svg')}}" class="image-cover h-8 w-8 hover:bg-gray-300 rounded p-1" title="Add a reaction" />
        </a>

        <div class="dropdown-menu dropdown-menu-left grid-cols-3 p-2" aria-labelledby="dropdownMenuButton">

            @foreach (setting()->getArray('reactions') as $reaction)
            <a up-target="#model-{{$model->id}}" up-reveal="false"
                class="dropdown-items"
                href="{{route('reaction.react', ['model' => $model->modelName, 'id' => $model->id, 'reaction'=> $reaction])}}">
                <img src="{{asset('/images/reactions/' . $reaction . '.png')}}" class="image-cover h-8 w-8 hover:bg-gray-300 p-1 rounded"
                    title="{{$reaction}}" />
            </a>
            @endforeach

        </div>
    </div>
    @endcan

</div>