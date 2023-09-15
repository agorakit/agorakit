<span class="rounded bg-gray-300">
    <a  href="{{ route('groups.files.show', [$file->group, $file]) }}">
        <img class="rounded w-4 h-4" src="{{ route('groups.files.thumbnail', [$file->group, $file]) }}" />
        {{ $file->name }}
    </a>
</span>