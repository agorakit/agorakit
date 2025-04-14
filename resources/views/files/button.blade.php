<span class="rounded bg-gray-300">
    <a  href="{{ route('files.show', [$file->group, $file]) }}">
        <img alt="{{ $file->name }}" class="rounded w-4 h-4" src="{{ route('files.thumbnail', [$file->group, $file]) }}" />
        {{ $file->name }}
    </a>
</span>