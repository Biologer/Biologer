<article class="box media">
    <div class="media-content">
        <div class="content">
            <h3 class="is-size-5">{{ $announcement->title }}</h3>
            <p>{{ $announcement->created_at->format('j.m.Y H:i') }} - {{ $announcement->authorName() }}</p>
        </div>
    </div>
    <div class="media-right">
        <a href="{{ route('announcements.show', $announcement) }}">Read</a>
    </div>
</article>
