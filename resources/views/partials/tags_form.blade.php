@include ('partials.selectize')


@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
@endpush


@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

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

        @foreach ($all_tags as $tag)
            <option value="{{$tag}}">{{$tag}}</option>
        @endforeach
    </select>
    <span id="tagshelp" class="help-block">{{trans('messages.tags_help')}}</span>
</div>
