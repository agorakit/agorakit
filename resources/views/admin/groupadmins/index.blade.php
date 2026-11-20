@extends('app')

@section('content')
    <h2>
        {{ t('Users') }}
    </h2>

    <table class="table data-table table-striped">
        <thead class="thead-dark">
            <tr>
                <th data-priority="1">{{ t('Name') }}</th>
                <th data-priority="1" style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">{{ t('Email') }}</th>
                <th>{{ t('Registration time') }}</th>
                <th>{{ t('Last activity') }}</th>
                <th data-priority="1" style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ t('Administrator') }}</th>
                <th data-priority="1" style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ t('Email verified') }}</th>
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
                            {{ t('yes') }}
                        @else
                            {{ t('no') }}
                        @endif
                    </td>

                    <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                        @if ($user->verified == 1)
                            {{ t('yes') }}
                        @else
                            {{ t('no') }}
                        @endif
                    </td>

                    <td>
                        {{ $user->getPreference('locale') }}
                    </td>

                    <td>
                        <a class="btn btn-secondary" href="{{ route('users.edit', $user) }}">{{ t('Edit') }}</a>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
