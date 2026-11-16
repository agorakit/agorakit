@extends('app')

@section('content')
    <h2>
        {{ trans('Users') }}
    </h2>

    <table class="table data-table table-striped">
        <thead class="thead-dark">
            <tr>
                <th data-priority="1">{{ trans('Name') }}</th>
                <th data-priority="1" style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">{{ trans('Email') }}</th>
                <th>{{ trans('Registration time') }}</th>
                <th>{{ trans('Last activity') }}</th>
                <th data-priority="1" style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ trans('Administrator') }}</th>
                <th data-priority="1" style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ trans('Email verified') }}</th>
                <th data-priority="1">Locale</th>
                <th data-priority="1"></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="{{ route('users.show', $user) }}" > {{ $user->name }}</a>
                    </td>

                    <td style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">
                        <a href="{{ route('users.show', $user) }}" > {{ $user->email }}</a>
                    </td>

                    <td data-order="{{ $user->created_at }}">
                        <a href="{{ route('users.show', $user) }}" >{{ $user->created_at }}</a>
                    </td>

                    <td data-order="{{ $user->updated_at }}">
                        <a href="{{ route('users.show', $user) }}" >{{ $user->updated_at }}</a>
                    </td>

                    <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                        @if ($user->isAdmin())
                            {{ trans('yes') }}
                        @else
                            {{ trans('no') }}
                        @endif
                    </td>

                    <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                        @if ($user->verified == 1)
                            {{ trans('yes') }}
                        @else
                            {{ trans('no') }}
                        @endif
                    </td>

                    <td>
                        {{ $user->getPreference('locale') }}
                    </td>

                    <td>
                        <a class="btn btn-secondary" href="{{ route('users.edit', $user) }}">{{ trans('Edit') }}</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
