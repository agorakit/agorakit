
{{-- Candidate for removal, use tags_input.blade instead --}}

<div class="form-group">
    <label for="tags">{{trans('messages.tags')}}</label>

    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.tags_help')}}
    </div>


    <select class="form-control js-tags"
    name="tags[]"
    multiple="multiple" data-tags="true">

    @if (isset($group) && $group->tagsAreLimited())
        @if (isset($model_tags))
            @foreach ($model_tags as $tag)
                <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
            @endforeach
        @endif

        @foreach ($group->allowedTags() as $tag)
            <option value="{{$tag->name}}">{{$tag->name}}</option>
        @endforeach
    @else

        @if (isset($model_tags))
            @foreach ($model_tags as $tag)
                <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
            @endforeach
        @endif

        @if (isset($group) && $group->exists)
            @foreach ($group->tagsUsed() as $tag)
                <option value="{{$tag->name}}">{{$tag->name}}</option>
            @endforeach
        @endif
    @endif

</select>


</div>
