
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
                    @unless ($selectedTags->contains($tag->name)))
                        <option value="{{$tag->name}}">
                            {{$tag->name}}
                        </option>
                    @endunless
                @else 
                    <option value="{{$tag->name}}">{{$tag->name}}</option>
                @endif
            @endforeach
    @endif
</select>


</div>
