@extends('app')

@section('content')

    @include('dashboard.tabs')

    <div>


        @push('js')
            <script>
                $(document).ready(function() {
                    @foreach ($all_tags as $tag)
                        $("#toggle-tag-{{ $tag->tag_id }}").click(function() {
                            $(".tag-group").hide();
                            $(".tag-{{ $tag->tag_id }}").show();
                            $(".tag-toggle").removeClass('active');
                            $(this).addClass('active');

                        });
                    @endforeach

                    $("#toggle-tag-all").click(function() {
                        $(".tag-group").show();
                        $(".tag-toggle").removeClass('active');
                        $(this).addClass('active');
                    });
                });
            </script>
        @endpush




        @foreach ($all_tags as $tag)
            <a class="btn btn-primary btn-sm tag-toggle" id="toggle-tag-{{ $tag->tag_id }}">{{ $tag->name }}</a>
        @endforeach

        <a class="btn btn-primary btn-sm tag-toggle active" id="toggle-tag-all">{{ trans('messages.show_all') }}</a>



        <div class="tab_content">
            @if ($groups)
                <table class="table table-hover special">
                    <thead>
                        <tr>
                            <th class="avatar"></th>
                            <th class="summary"></th>
                            <th class="hidden-xs" style="width:100px"></th>
                            <th class="" style="width:100px"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($groups as $group)
                            <tr class="tag-group @foreach ($group->tags as $tag)tag-{{ $tag->tag_id }} @endforeach">
                                <td class="avatar"><span class="avatar"><img class="rounded"
                                            src="{{ route('groups.cover', [$group, 'small']) }}" /></span></td>
                                <td class="content">
                                    <a href="{{ route('groups.show', $group) }}">
                                        <span class="name">{{ $group->name }}

                                            @if ($group->isOpen())
                                                <i class="fa fa-globe" title="{{ trans('group.open') }}"></i>
                                            @elseif ($group->isClosed())
                                                <i class="fa fa-lock" title="{{ trans('group.closed') }}"></i>
                                            @else
                                                <i class="fa fa-eye-slash" title="{{ trans('group.secret') }}"></i>
                                            @endif
                                        </span>

                                        <span class="summary">{{ summary($group->body) }}</span>
                                        <br />
                                    </a>
                                    <span class="group-name">
                                        @foreach ($group->tags as $tag)
                                            @include('tags.tag')
                                        @endforeach
                                    </span>

                                </td>

                                <td class="date hidden-xs">
                                    {{ $group->updated_at->diffForHumans() }}
                                </td>

                                <td>
                                    @unless ($group->isMember())
                                        @can('join', $group)
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ action('GroupMembershipController@store', $group->id) }}"><i
                                                    class="fa fa-sign-in"></i>
                                                {{ trans('group.join') }}
                                            </a>
                                        @endcan
                                    @endunless

                                </td>

                            </tr>
                        @empty
                            {{ trans('messages.nothing_yet') }}
                        @endforelse
                    </tbody>
                </table>
            @else
                {{ trans('messages.nothing_yet') }}
            @endif
        </div>





    </div>

@endsection
