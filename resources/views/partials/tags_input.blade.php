<div class="form-group">
    <label for="tags">{{ trans('messages.tags') }}</label>

    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{ trans('messages.tags_help') }}
    </div>

    <div class="d-flex flex-wrap my-4">

        @if (isset($selectedTags))
            @foreach ($selectedTags as $tag)
                <div class="me-1 mb-2">
                    <input type="checkbox" checked value="{{ $tag->normalized }}" name="tags[]"
                        id="{{ $tag->normalized }}" />
                    <label for="{{ $tag->normalized }}"> {{ $tag->name }} </label>
                </div>
            @endforeach
        @endif

        @if (isset($allowedTags))
            @foreach ($allowedTags as $tag)
                @if (isset($selectedTags))
                    @unless ($selectedTags->contains($tag->name))
                        <div class="me-1 mb-2">
                            <input type="checkbox" value="{{ $tag->normalized }}" name="tags[]"
                                id="{{ $tag->normalized }}" />
                            <label for="{{ $tag->normalized }}"> {{ $tag->name }} </label>
                        </div>
                    @endunless
                @else
                    <div class="me-1 mb-2">
                        <input type="checkbox" value="{{ $tag->normalized }}" name="tags[]"
                            id="{{ $tag->normalized }}" />
                        <label for="{{ $tag->normalized }}"> {{ $tag->name }} </label>
                    </div>
                @endif
            @endforeach
        @endif

    </div>

    @if ($newTagsAllowed)
        <label>
            {{ trans('messages.new_tags') }} :
        </label>
        <select class="form-control js-tags" name="tags[]" multiple="multiple" data-tags="true">
        </select>
    @endif

</div>
