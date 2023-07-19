@extends('app')

@section('content')
    <h2>
        {{ trans('messages.users') }}
    </h2>

    <table class="table data-table table-striped">
        <thead class="thead-dark">
            <tr>
                <th data-priority="1">{{ trans('messages.name') }}</th>
                <th data-priority="1" style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">{{ trans('messages.email') }}</th>
                <th>{{ trans('messages.registration_time') }}</th>
                <th>{{ trans('messages.last_activity') }}</th>
                <th data-priority="1" style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ trans('messages.admin') }}</th>
                <th data-priority="1" style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ trans('messages.email_verified') }}</th>
                <th data-priority="1">Locale</th>
                <th data-priority="1"></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="{{ route('users.show', $user) }}" up-follow> {{ $user->name }}</a>
                    </td>

                    <td style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">
                        <a href="{{ route('users.show', $user) }}" up-follow> {{ $user->email }}</a>
                    </td>

                    <td data-order="{{ $user->created_at }}">
                        <a href="{{ route('users.show', $user) }}" up-follow>{{ $user->created_at }}</a>
                    </td>

                    <td data-order="{{ $user->updated_at }}">
                        <a href="{{ route('users.show', $user) }}" up-follow>{{ $user->updated_at }}</a>
                    </td>

                    <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                        @if ($user->isAdmin())
                            {{ trans('messages.yes') }}
                        @else
                            {{ trans('messages.no') }}
                        @endif
                    </td>

                    <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                        @if ($user->verified == 1)
                            {{ trans('messages.yes') }}
                        @else
                            {{ trans('messages.no') }}
                        @endif
                    </td>

                    <td>
                        {{ $user->getPreference('locale') }}
                    </td>

                    <td>
                        <a class="btn btn-secondary" href="{{ route('users.edit', $user) }}">{{ trans('messages.edit') }}</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
