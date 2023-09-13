
<div class="form-group">
    <label for="tags">{{trans('messages.tags')}}</label>

    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{trans('messages.tags_help')}}
    </div>

    <div class="flex flex-wrap my-4">

    @if (isset($selectedTags))
            @foreach ($selectedTags as $tag)
                <div class="me-2 mb-2 bg-gray-300 py-1 px-2 rounded-lg hover:bg-gray-400 cursor-pointer">
                    <input type=checkbox checked value="{{$tag->normalized}}" id="{{$tag->normalized}}"  name="tags[]"/>
                    <label for="{{$tag->normalized}}" class="cursor-pointer">
                    <span class="inline-block w-2 h-2 rounded-sm" style="background-color: {{$tag->color}}"></span>
                     {{$tag->name}}
                    </label>
                </div>
            @endforeach
    @endif


    @if (isset($allowedTags))
            @foreach ($allowedTags as $tag)
                @if (isset($selectedTags))
                    @unless ($selectedTags->contains($tag->name))
                        <div class="mr-4">
                            <input type="checkbox" value="{{$tag->normalized}}" name="tags[]" id="{{$tag->normalized}}"/>
                            <label for="{{$tag->normalized}}">
                            <span class="inline-block w-2 h-2 rounded-sm" style="background-color: {{$tag->color}}"></span>
                            {{$tag->name}}
                            </label>
                        </div>
                    @endunless
                @else 
                    <div class="mr-4">
                        <input type="checkbox" value="{{$tag->normalized}}" name="tags[]" id="{{$tag->normalized}}"/>
                        <label for="{{$tag->normalized}}">
                        <span class="inline-block w-2 h-2 rounded-sm" style="background-color: {{$tag->color}}"></span>
                        {{$tag->name}}
                        </label>
                    </div>
                @endif
            @endforeach
    @endif

    </div>


    @if ($newTagsAllowed)
        <label>
            {{trans('messages.new_tags')}} : 
        </label>
        <select class="form-control js-tags" name="tags[]" multiple="multiple"  data-tags="true">
        </select>
    @endif


</div>
