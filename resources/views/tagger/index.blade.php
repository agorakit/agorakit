@extends('dialog')

@section('content')


    <h2 class="mb-3">@lang('Edit tags') : <strong>"{{$model->name}}"</strong></h2>

    <div class="tagger">

        <div class="mb-3">
            @foreach ($allowed_tags as $allowed_tag)

                <a up-target=".tagger" up-cache="false" up-history="false" href="{{route('tagger.tag', [$type, $id, $allowed_tag->name])}}">

                    @if ($used_tags->contains($allowed_tag))
                        <span class="badge badge-pill" style="font-size: 1rem; color: white; background-color: {{$allowed_tag->color}}">
                            <i class="fas fa-check-circle"></i>
                            {{$allowed_tag->name}}
                        </span>
                    @else
                        <span class="badge badge-pill" style="font-size: 1rem; color: white; background-color: {{$allowed_tag->color}}; opacity: 0.4">
                            <i class="fas fa-circle"></i>
                            <span style="opacity: 1">{{$allowed_tag->name}}</span>
                        </span>
                    @endif


                </span>
            </a>

        @endforeach
    </div>

    @if (!$model->group->tagsAreLimited())
        <form method="post" class="form-inline mb-3" up-target=".tagger" up-history="false">
            <div class="form-group">
                @csrf
                <input type="text" name="name" class="form-control" placeholder="@lang('Type a tag to add it')">
                <input type="submit" value="@lang('Add')" class="form-control btn btn-secondary ml-2">
            </div>
        </form>
    @endif

</div>

<div class="mt-5 d-flex justify-content-end">
    <a href="{{$return_to}}" up-cache="false" up-target=".main-content" class="btn btn-primary btn-lg">@lang('Done')</a>
</div>


@endsection
