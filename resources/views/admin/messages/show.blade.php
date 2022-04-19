@extends('app')



@section('content')

<div class="tab_content">

    <h2>
        {{$message->subject}}
    </h2>




    <div>
        ID : {{$message->id}}
    </div>
    <div>
        Subject : {{$message->subject}}
    </div>
    <div>
        From : {{$message->from}}
    </div>
    <div>
        Status : {{$message->status}}

        (
        @if ($message->status == App\Models\Message::POSTED)
        Posted
        @endif
        @if ($message->status == App\Models\Message::NEEDS_VALIDATION)
        Needs validation
        @endif

        @if ($message->status == App\Models\Message::CREATED)
        Created
        @endif

        @if ($message->status == App\Models\Message::BOUNCED)
        Bounced
        @endif

        @if ($message->status == App\Models\Message::INVALID)
        Invalid
        @endif

        @if ($message->status == App\Models\Message::AUTOMATED)
        Automated
        @endif

        @if ($message->status == App\Models\Message::ERROR)
        Error
        @endif

        @if ($message->status == App\Models\Message::SPAM)
        Spam
        @endif
        )
    </div>


    <div>
        Recipients :

        @foreach ($message->extractRecipients() as $recipient)
        {{$recipient}}
        @endforeach
    </div>


    <div>Extracted body :</div>
    <div class="border border-gray-700 p-2">

        {!!$message->extractText()!!}
    </div>


    <div>Raw message :</div>
    <pre>{{$message->raw}}
    </pre>

    @endsection