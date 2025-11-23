<div class="container">
    <div class="columns is-multiline">
        @foreach($groups as $group)
            <div class="column is-one-third has-text-centered">
                <a href="{{ $group->firstSpecies ? route('groups.species.show', ['group' => $group, 'species' => $group->firstSpecies]) : '' }}" title="{{ $group->name_with_fallback }}">
                    <div class="view-group-card hover:shadow-link">
                        <div
                            class="view-group-card__image {{ $group->image_url ? '' : 'view-group-card__image--default' }}"
                            title="{{ $group->name_with_fallback }}"
                        >
                            <img src="{{ $group->image_url ?? \App\Models\ViewGroup::defaultImage() }}">
                        </div>

                        <div class="view-group-card__content">
                            <div>
                                {{ $group->name_with_fallback }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
