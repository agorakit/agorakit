@props(['model'])

<div class="flex">
    <div id="model-{{$model->id}}">
        @foreach ($model->reactions as $reaction)


        @if (Auth::check() && Auth::user()->is($reaction->user))
        <a up-target="#model-{{$model->id}}" up-reveal="false"
            href="{{route('reaction.unreact', ['model' => 'comment', 'id' => $model->id])}}">
            <img src="{{asset('/images/reactions/' . $reaction->type . '.png')}}" class="image-cover h-8 w-8"
                title="{{ $reaction->user->name }} - click to remove" />
        </a>
        @else

        <img src="{{asset('/images/reactions/' . $reaction->type . '.png')}}" class="image-cover h-8 w-8"
            title="{{ $reaction->user->name }}" />

        @endif
        @endforeach
    </div>

    <div class="dropdown">
        <a class="rounded-full hover:bg-gray-400 w-10 h-10 flex items-center justify-center" type="button"
            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-h"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">


            @foreach (setting()->getArray('reactions') as $reaction)
            <a class="dropdown-item" up-target="#model-{{$model->id}}" up-reveal="false"
                class="bg-gray-300 rounded p-1 text-sm mr-2"
                href="{{route('reaction.react', ['model' => 'comment', 'id' => $model->id, 'reaction'=> $reaction])}}">
                <img src="{{asset('/images/reactions/' . $reaction . '.png')}}" class="image-cover h-8 w-8" />
            </a>
            @endforeach
        </div>
    </div>

</div>