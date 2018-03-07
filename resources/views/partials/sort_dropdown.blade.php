
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{trans('messages.sort')}}
        @if (request()->get('sort'))
            : @lang('messages.sort_by_'.request()->get('sort').'_'.request()->get('dir'))
        @else
            : @lang('messages.sort_by_created_at_desc')
        @endif
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a up-target="files" class="dropdown-item" href="{{request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'desc', 'page' => null])}}">
            <i class="fa fa-sort-amount-desc"></i>
            @lang('messages.sort_by_created_at_desc')
        </a>
        <a up-target="files" class="dropdown-item" href="{{request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'asc', 'page' => null])}}">
            <i class="fa fa-sort-amount-asc"></i>
            @lang('messages.sort_by_created_at_asc')
        </a>
        <a up-target="files" class="dropdown-item" href="{{request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc', 'page' => null])}}">
            <i class="fa fa-sort-alpha-asc"></i>
            @lang('messages.sort_by_name_asc')
        </a>
        <a up-target="files" class="dropdown-item" href="{{request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'desc', 'page' => null])}}">
            <i class="fa fa-sort-alpha-desc"></i>
            @lang('messages.sort_by_name_desc')
        </a>
        <a up-target="files" class="dropdown-item" href="{{request()->fullUrlWithQuery(['sort' => 'filesize', 'dir' => 'asc', 'page' => null])}}">
            <i class="fa fa-sort-numeric-asc"></i>
            @lang('messages.sort_by_filesize_asc')
        </a>
        <a up-target="files" class="dropdown-item" href="{{request()->fullUrlWithQuery(['sort' => 'filesize', 'dir' => 'desc', 'page' => null])}}">
            <i class="fa fa-sort-numeric-desc"></i>
            @lang('messages.sort_by_filesize_desc')
        </a>

    </div>
</div>
