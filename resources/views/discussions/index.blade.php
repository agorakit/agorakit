@extends('app')

@section('content')
    @include('discussions.list', ['discussions' => $discussions])


 <div class="mt-16 text-secondary">
        <a class="btn btn-secondary" href="{{ route('discussions.feed') }}"><i class="fas fa-rss"></i> RSS</a>
    </div>
@endsection
