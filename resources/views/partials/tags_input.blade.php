
<div class="form-group">
    <label for="tags">{{trans('messages.tags')}}</label>

    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.tags_help')}}
    </div>


    <select class="form-control js-tags"
    name="tags[]"
    multiple="multiple"
    @if ($newTagsAllowed)
        data-tags="true"
    @endif>

    @if (isset($selectedTags))
            @foreach ($selectedTags as $tag)
                <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
            @endforeach
    @endif

    @if (isset($allowedTags))
            @foreach ($allowedTags as $tag)
                @if (isset($selectedTags))
                    @unless (in_array($tag, $selectedTags))
                    <option value="{{$tag}}">{{$tag}}</option>
                    @endunless
                @else 
                <option value="{{$tag}}">{{$tag}}</option>
                @endif
            @endforeach
    @endif
</select>


</div>
