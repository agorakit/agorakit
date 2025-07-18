@extends('app')

@section('content')
    <h2>
        {{ $message->subject }}
    </h2>

    <div>
        ID : {{ $message->id }}
    </div>
    <div>
        Subject : {{ $message->subject }}
    </div>
    <div>
        From : {{ $message->from }}
    </div>
    <div>
        Status : {{ $message->status }}

        (
        @if ($message->status == Agorakit\Message::POSTED)
            Posted
        @endif
        @if ($message->status == Agorakit\Message::NEEDS_VALIDATION)
            Needs validation
        @endif

        @if ($message->status == Agorakit\Message::CREATED)
            Created
        @endif

        @if ($message->status == Agorakit\Message::BOUNCED)
            Bounced
        @endif

        @if ($message->status == Agorakit\Message::INVALID)
            Invalid
        @endif

        @if ($message->status == Agorakit\Message::AUTOMATED)
            Automated
        @endif

        @if ($message->status == Agorakit\Message::ERROR)
            Error
        @endif

        @if ($message->status == Agorakit\Message::SPAM)
            Spam
        @endif
        )
    </div>

    <div>
        Recipients :

        @foreach ($message->extractRecipients() as $recipient)
            {{ $recipient }}
        @endforeach
    </div>

    <div>Extracted body :</div>
    <div class="border border-gray-700 p-2">

        {!! $message->extractText() !!}
    </div>

    <div>Raw message :</div>
    <pre>{{ $message->raw }}
    </pre>
@endsection
