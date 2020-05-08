@extends('app')

@section('content')

    <h1>Recover deleted data</h1>
    <p>Just click the recover button for the item you want to recover and it will be directly undeleted</p>

    <h2>Groups</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Deletion time</th>
            </tr>
        </thead>

        @foreach ($groups as $group)
            <tr>
                <td>{{$group->name}}</td>
                <td>{{$group->deleted_at}}</td>
                <td><a up-follow href="{{route('admin.restore', ['group', $group] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Discussions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Group</th>
                <th>Author</th>
                <th>Deletion time</th>
            </tr>
        </thead>
        @foreach ($discussions as $discussion)
            <tr>
                <td>{{$discussion->name}}</td>
                <td>
                    @if ($discussion->group)
                        {{$discussion->group->name}}
                    @else
                        <small>Group is probably deleted we will try to recover it</small>
                    @endif
                </td>
                <td>
                    @if ($discussion->user)
                        {{$discussion->user->name}}
                    @endif
                </td>
                <td>{{$discussion->deleted_at}}</td>
                <td><a up-follow href="{{route('admin.restore', ['discussion', $discussion] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Comments</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Author</th>
                <th>Deletion time</th>
            </tr>
        </thead>
        @foreach ($comments as $comment)
            <tr>
                <td>{{summary($comment->body)}}</td>
                <td>
                    @if ($comment->user)
                        {{$comment->user->name}}
                    @endif
                </td>
                <td>{{$comment->deleted_at}}</td>
                <td><a up-follow href="{{route('admin.restore', ['comment', $comment] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Files</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Group</th>
                <th>Author</th>
                <th>Deletion time</th>
            </tr>
        </thead>
        @foreach ($files as $file)
            <tr>
                <td>{{$file->name}}</td>
                <td>
                    @if ($file->group)
                        {{$file->group->name}}
                    @else
                        <small>Group is probably deleted we will try to recover it</small>
                    @endif
                </td>
                <td>
                    @if ($file->user)
                        {{$file->user->name}}
                    @endif
                </td>
                <td>{{$file->deleted_at}}</td>
                <td><a up-follow href="{{route('admin.restore', ['file', $file] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Actions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Group</th>
                <th>Author</th>
                <th>Deletion time</th>
            </tr>
        </thead>
        @foreach ($actions as $action)
            <tr>
                <td>{{$action->name}}</td>
                <td>
                    @if ($action->group)
                        {{$action->group->name}}
                    @else
                        <small>Group is probably deleted we will try to recover it</small>
                    @endif
                </td>
                <td>
                    @if ($action->user)
                        {{$action->user->name}}
                    @endif
                </td>
                <td>{{$action->deleted_at}}</td>
                <td><a up-follow href="{{route('admin.restore', ['action', $action] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>



@endsection
