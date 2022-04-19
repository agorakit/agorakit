@extends('app')



@section('content')

<div class="tab_content">

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
            @foreach( $messages as $message )
            <tr>
                <td>
                    {{$message->id}}
                </td>

                <td>
                    {{$message->subject}}

                </td>

                <td>
                    {{$message->from}}
                </td>

                <td>
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

                </td>

                <td>
                    <a class="btn btn-primary" href="{{route('admin.messages.show', $message)}}">View</a>
                </td>


            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection