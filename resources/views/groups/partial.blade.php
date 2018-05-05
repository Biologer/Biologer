<div class="columns is-multiline">
    @foreach($groups as $group)
        <div class="column is-one-third has-text-centered">
            <a href="{{ $group->firstSpecies()->url() }}">{{ $group->name }}</a>
        </div>
    @endforeach
</div>
