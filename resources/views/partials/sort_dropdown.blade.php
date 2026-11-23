<div class="dropdown">
    <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
        id="dropdownMenuButton" type="button">
        {{ trans('Sort') }}
        @if (request()->get('sort'))
            : {{ trans(request()->get('sort')) }} {{ trans(request()->get('dir')) }}
        @else
            : {{ trans('Sort by creation date descending') }}
        @endif
    </button>
    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'desc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-amount-desc"></i>
            {{ trans('Sort by creation date descending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'asc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-amount-asc"></i>
            {{ trans('Sort by creation date ascending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-alpha-asc"></i>
            {{ trans('Sort by name ascending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'desc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-alpha-desc"></i>
            {{ trans('Sort by name descending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'filesize', 'dir' => 'asc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-numeric-asc"></i>
            {{ trans('Sort by filesize ascending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'filesize', 'dir' => 'desc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-numeric-desc"></i>
            {{ trans('Sort by filesize descending') }}
        </a>

    </div>
</div>
