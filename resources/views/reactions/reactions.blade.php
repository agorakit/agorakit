<div id="model-{{ $model->id }}">

    <div class="d-flex align-items-center mt-2">
        @foreach ($model->reactions as $reaction)
            @if (Auth::check() && Auth::user()->is($reaction->user))
                <a href="{{ route('reaction.unreact', ['model' => $model->modelName, 'id' => $model->id]) }}" up-target="#model-{{ $model->id }}">
                    <img class="reaction" src="{{ asset('/images/reactions/' . $reaction->type . '.png') }}" title="{{ $reaction->user->name }} - click to remove"
                        />
                </a>
            @else
                @if ($reaction->user)
                    <img class="reaction" src="{{ asset('/images/reactions/' . $reaction->type . '.png') }}" title="{{ $reaction->user->name }}" />
                @endif
            @endif
        @endforeach

        @can('react', $model)
            <div class="dropdown">
                <a class="cursor-pointer d-flex align-items-center bg-gray-s300 text-gray-700 rounded-lg text-xs opacity-25 hover:opacity-100" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="false">
                    <img class="reaction" src="{{ asset('/images/reactions/reaction.svg') }}" title="Add a reaction" />

                </a>

                <div class="dropdown-menu dropdown-menu-left grid-cols-3 p-2" aria-labelledby="dropdownMenuButton">

                    @foreach (setting()->getArray('reactions') as $reaction)
                        <a class="no-underline" href="{{ route('reaction.react', ['model' => $model->modelName, 'id' => $model->id, 'reaction' => $reaction]) }}"
                            up-target="#model-{{ $model->id }}">
                            <img class="reaction" src="{{ asset('/images/reactions/' . $reaction . '.png') }}" title="{{ $reaction }}" />
                        </a>
                    @endforeach

                </div>
            </div>
        @endcan

    </div>
</div>
