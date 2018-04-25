@foreach($groups->chunk(3) as $chunk)
    <div class="columns">
        @foreach($chunk as $group)
            <div class="column has-text-centered">
                <a href="{{ $group->firstSpecies()->url() }}">{{ $group->name }}</a>
            </div>
        @endforeach
    </div>
@endforeach
