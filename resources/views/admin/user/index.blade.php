@extends('app')

@include('partials.datatables')

@push('js')
    <script>
    $(document).ready(function() {
        $('.table').DataTable();
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
                    <th>{{ trans('messages.email') }}</th>
                    <th>{{ trans('messages.registration_time') }}</th>
                    <th>{{ trans('messages.last_activity') }}</th>
                    <th>{{ trans('messages.admin') }}</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach( $users as $user )
                    <tr>
                        <td>
                            <a href="{{ action('UserController@show', $user->id) }}"> {{ $user->name }}</a>
                        </td>

                        <td>
                            <a href="{{ action('UserController@show', $user->id) }}"> {{ $user->email }}</a>
                        </td>

                        <td data-order="{{ $user->created_at }}">
                            <a href="{{ action('UserController@show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
                        </td>

                        <td data-order="{{ $user->updated_at }}">
                            <a href="{{ action('UserController@show', $user->id) }}">{{ $user->updated_at->diffForHumans() }}</a>
                        </td>

                        <td>
                            @if ($user->isAdmin())
                                {{trans('messages.admin')}}
                            @else

                            @endif
                        </td>

                        <td>
                            <a href="{{ action('UserController@edit', $user->id) }}">{{trans('messages.edit')}}</a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
