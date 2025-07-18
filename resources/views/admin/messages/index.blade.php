@extends('app')

@section('content')
    <h2>
        Messages
    </h2>

    <table class="table data-table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Subject</th>

                <th>From</th>

                <th>Status</th>

                <th>Actions</th>

            </tr>
        </thead>

        <tbody>
            @foreach ($messages as $message)
                <tr>
                    <td>
                        {{ $message->id }}
                    </td>

                    <td>
                        {{ $message->subject }}

                    </td>

                    <td>
                        {{ $message->from }}
                    </td>

                    <td>
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

                    </td>

                    <td>
                        <a class="btn btn-primary" href="{{ route('admin.messages.show', $message) }}">View</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
