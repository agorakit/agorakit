@extends('app')



@section('content')

    <div class="tab_content">

        <h2>
           Groups stats
        </h2>


        <table class="table data-table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Group name</th>
                    <th>Last updated</th>
                    <th>Users</th>
                    <th>Discussions</th>
                </tr>
            </thead>

            <tbody>
                @foreach( $groups as $group )
                    <tr>
                        <td>
                            <a  href="{{ route('groups.show', $group) }}"> {{ $group->name }}</a>
                        </td>

                        <td>{{$group->updated_at}}</td>

                        
                        <td>{{$group->users_count}}</td>

                        <td>{{$group->discussions_count}}</td>

                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
