{{$model->link()}}

{{$model->reactionSummary()}}

@foreach ($model->reactionSummary() as $reaction => $amount)

<div class="flex">
    
    <div>
        @if ($reaction == 'like') [[icon like]] @endif
        @if ($reaction == 'dislike') [[icon dislike]] @endif
    </div>
    <div>
        {{$amount}}
    </div>
    
</div>

@endforeach