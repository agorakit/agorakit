@extends('app')

@include('partials.datatables')

@push('js')
    <script>
    $(document).ready(function() {
        $('.table').DataTable( {
          responsive: true,
          dom: 'Bfrtip',
          buttons: [
            'csv', 'excel', 'print'
        ]
        });
    } );
    </script>
@endpush


@section('content')

    <div class="tab_content">

        <h2>
            {{ trans('messages.users') }}
        </h2>


        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ trans('messages.name') }}</th>
                    <th style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">{{ trans('messages.email') }}</th>
                    <th>{{ trans('messages.registration_time') }}</th>
                    <th>{{ trans('messages.last_activity') }}</th>
                    <th style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ trans('messages.admin') }}</th>
                    <th style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">{{ trans('messages.email_verified') }}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach( $users as $user )
                    <tr>
                        <td>
                            <a href="{{ route('users.show', $user) }}"> {{ $user->name }}</a>
                        </td>

                        <td style="max-width: 150px; overflow: hidden;text-overflow: ellipsis;">
                            <a href="{{ route('users.show', $user) }}"> {{ $user->email }}</a>
                        </td>

                        <td data-order="{{ $user->created_at }}">
                            <a href="{{ route('users.show', $user) }}">{{ $user->created_at->diffForHumans() }}</a>
                        </td>

                        <td data-order="{{ $user->updated_at }}">
                            <a href="{{ route('users.show', $user) }}">{{ $user->updated_at->diffForHumans() }}</a>
                        </td>

                        <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                            @if ($user->isAdmin())
                                {{trans('messages.yes')}}
                            @else
                                {{trans('messages.no')}}
                            @endif
                        </td>

                        <td style="max-width: 50px; overflow: hidden;text-overflow: ellipsis;">
                            @if ($user->verified == 1 )
                                {{trans('messages.yes')}}
                            @else
                                {{trans('messages.no')}}
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('users.edit', $user) }}">{{trans('messages.edit')}}</a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
