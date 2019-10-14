
<div class="form-group">
    <label for="tags">{{trans('messages.tags')}}</label>
    <select class="form-control tags"
    name="tags[]"
    multiple="multiple"
    @unless (isset($group) && $group->tagsAreLimited())
        data-allow-new-tags="true"
    @endunless
    >

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
<span id="tagshelp" class="help-block">{{trans('messages.tags_help')}}</span>
</div>
