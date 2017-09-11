@include ('partials.selectize')



@push('js')
    <script>
    $( document ).ready(function() {
        $('.tags').selectize({
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
    });
    </script>
@endpush


<div class="form-group">
    <label for="tags">{{trans('messages.tags')}}</label>
    <select class="form-control tags" name="tags[]" multiple="multiple">
        @if (isset($model_tags))
            @foreach ($model_tags as $tag)
                <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
            @endforeach
        @endif

        @if (isset($all_tags))
            @foreach ($all_tags as $tag)
                <option value="{{$tag}}">{{$tag}}</option>
            @endforeach
        @endif
    </select>
    <span id="tagshelp" class="help-block">{{trans('messages.tags_help')}}</span>
</div>
