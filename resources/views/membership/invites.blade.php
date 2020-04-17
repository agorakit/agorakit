@extends('dialog')

@section('content')

    @if ($memberships->count() > 0)


        <h1>@lang('Pending invites')</h1>
        <p>@lang('Someone invited you to the following group(s). You may accept or discard the invitation here.')</p>

        @foreach ($memberships as $membership)
            <div class="d-flex justify-content-between @unless ($loop->last) border-bottom @endunless mb-3 pb-2">
                <div class="mr-4">
                    <strong>{{$membership->group->name}}</strong>
                    <div>{{summary($membership->group->body) }}</div>
                </div>

                <div>
                    <div class="btn-group">
                        <a href="{{route('invites.accept', $membership)}}" class="btn btn-primary" up-target=".dialog">@lang('Accept')</a>
                        <a href="{{route('invites.deny', $membership)}}" class="btn btn-danger" up-target=".dialog">@lang('Discard')</a>
                    </div>
                </div>
            </div>

        @endforeach


    @else
        <h1>@lang('You have no pending invites')</h1>

    @endif



@endsection
