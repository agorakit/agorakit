<div class="form-group">
    <label for="tags">{{ trans('messages.tags') }}</label>

    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{ trans('messages.tags_help') }}
    </div>

    <select class="form-control js-tags" name="tags[]" multiple="multiple"
        @if ($newTagsAllowed) data-tags="true" @endif>
        @if (isset($allowedTags))
            @foreach ($allowedTags as $tag)
                <option value="{{ $tag }}">{{ $tag }}</option>
            @endforeach
        @endif

        @if (isset($selectedTags))
            @foreach ($selectedTags as $tag)
                <option value="{{ $tag }}" selected>{{ $tag }}</option>
            @endforeach
        @endif
    </select>

</div>
