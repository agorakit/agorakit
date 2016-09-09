@extends('app')

@section('js')
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.table').DataTable();
    } );
    </script>
@endsection

@section('css')
    {!! Html::style('https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css') !!}
@endsection

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

                        <td>
                            <a href="{{ action('UserController@show', $user->id) }}">{{ $user->created_at->diffForHumans() }}</a>
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
