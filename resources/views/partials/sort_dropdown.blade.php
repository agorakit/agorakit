<div class="dropdown">
    <button aria-expanded="false" aria-haspopup="true" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"
        id="dropdownMenuButton" type="button">
        {{ t('Sort') }}
        @if (request()->get('sort'))
            : {{ trans(request()->get('sort')) }} {{ trans(request()->get('dir')) }}
        @else
            : {{ t('Sort by creation date descending') }}
        @endif
    </button>
    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'desc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-amount-desc"></i>
            {{ t('Sort by creation date descending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => 'asc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-amount-asc"></i>
            {{ t('Sort by creation date ascending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'asc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-alpha-asc"></i>
            {{ t('Sort by name ascending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'dir' => 'desc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-alpha-desc"></i>
            {{ t('Sort by name descending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'filesize', 'dir' => 'asc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-numeric-asc"></i>
            {{ t('Sort by filesize ascending') }}
        </a>
        <a class="dropdown-item"
            href="{{ request()->fullUrlWithQuery(['sort' => 'filesize', 'dir' => 'desc', 'page' => null]) }}"
            up-target="files">
            <i class="fa fa-sort-numeric-desc"></i>
            {{ t('Sort by filesize descending') }}
        </a>

    </div>
</div>
