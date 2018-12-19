<article class="box flex flex-col tablet:flex-row">
    <div class="flex-1">
        <div class="content">
            <h3 class="is-size-5">{{ $announcement->title }}</h3>
            <p>{{ $announcement->created_at->format('j.m.Y H:i') }} - {{ $announcement->authorName() }}</p>
        </div>
    </div>
    <div>
        <a href="{{ route('announcements.show', $announcement) }}">{{ __('pages.announcements.read') }}</a>
    </div>
</article>
