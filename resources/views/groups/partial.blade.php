<div class="container">
    <div class="columns is-multiline">
        @foreach($groups as $group)
            <div class="column is-one-third has-text-centered">
                <a href="{{ $group->firstSpecies() ? route('groups.species.show', ['group' => $group, 'species' => $group->firstSpecies()]) : '' }}" title="{{ $group->name }}">
                    <div class="view-group-card hover:shadow-link">
                        <div
                            class="view-group-card__image"
                            style="background-image: url('{{ $group->image_url ?? \App\ViewGroup::defaultImage() }}')"
                            title="{{ $group->name }}"
                        ></div>

                        <div class="view-group-card__content">
                            <div>
                                {{ $group->name }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
