@extends('app')

@section('content')

    <h1>Recover deleted data</h1>
    <p>Just click the recover button for the itme you want to recover and it will be directly undeleted</p>

    <h2>Groups</h2>
    <table class="table">
        @foreach ($groups as $group)
            <tr>
                <td>{{$group->name}}</td>
                <td>{{$group->deleted_at}}</td>
                <td><a href="{{route('admin.restore', ['group', $group->id] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Discussions</h2>
    <table class="table">
        @foreach ($discussions as $discussion)
            <tr>
                <td>{{$discussion->name}}</td>
                <td>{{$discussion->group()->withTrashed()->first()->name}}</td>
                <td>{{$discussion->deleted_at}}</td>
                <td><a href="{{route('admin.restore', ['discussion', $discussion->id] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Comments</h2>
    <table class="table">
        @foreach ($comments as $comment)
            <tr>
                <td>{{summary($comment->body)}}</td>
                <td>{{$comment->deleted_at}}</td>
                <td><a href="{{route('admin.restore', ['comment', $comment->id] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Files</h2>
    <table class="table">
        @foreach ($files as $file)
            <tr>
                <td>{{summary($file->name)}}</td>
                <td>{{$file->deleted_at}}</td>
                <td><a href="{{route('admin.restore', ['file', $file->id] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>


    <h2>Actions</h2>
    <table class="table">
        @foreach ($actions as $action)
            <tr>
                <td>{{summary($action->name)}}</td>
                <td>{{$action->deleted_at}}</td>
                <td><a href="{{route('admin.restore', ['action', $action->id] )}}">Recover</a></td>
            </tr>
        @endforeach
    </table>



@endsection
