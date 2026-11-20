<div class="form-group">
    <label for="tags">{{ t('Tags') }}</label>

    <div class="small-help">
        <i class="fas fa-info-circle"></i>
        {{ t('Choose or type one or more tag (keywords if you prefer). This will help others to find your contribution. In the list you will see the tags already used elsewhere. Try to follow the existing tags.') }}
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
