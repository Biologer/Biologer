@extends('layouts.main', ['title' => __('about.citation')])

{{-- 1. CSRF token for post request --}}
@section('header')
@parent
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<section class="section content">
    <div class="container">
        <h1>{{ __('about.citation_title') }}</h1>

        <p>{{ __('about.citation_intro_text') }}</p>

        <div class="box has-background-white-bis" style="padding: 20px;">
            <p style="font-family: monospace; font-size: 0.95em;">
                Popović M, Vasić N, Koren T, Burić I, Živanović N, Kulijer D, Golubović A (2020)
                Biologer: an open platform for collecting biodiversity data.
                <i>Biodiversity Data Journal</i> 8: e53014. DOI:
                <a href="https://doi.org/10.3897/BDJ.8.e53014">10.3897/BDJ.8.e53014</a>.
            </p>
        </div>

        <hr>

        <p>{{ __('about.citation_intro_text_2') }}</p>

        <div class="box has-background-white-bis" style="padding: 20px;">
            <p style="font-family: monospace; font-size: 0.95em;">
                {{ $communityName }} ({{ $platformYear }})
                {{ __('about.community_desc') }}.
                {{ __('about.community_url') }}: <a href="{{ $platformUrl }}">{{ $platformUrl }}</a>
                {{ __('about.community_assessed') }}:
                {{ \Carbon\Carbon::now()->translatedFormat('d.m.Y.') }}
            </p>
        </div>

        <p>{{ __('about.citation_intro_text_3') }}</p>

        <div class="box is-shadowless has-background-light p-5">
            <div class="field">
                <div class="control is-expanded">
                    <select class="select2-taxon-search" id="taxon-search" style="width: 100%;">
                        <option value="">The value</option>
                    </select>
                </div>
            </div>

            <div id="citation-results-container">
                <p class="has-text-grey" id="citation-prompt">{{ __('about.select_taxon_prompt') }}</p>

                {{-- Kartica za prikaz citata (inicijalno skrivena) --}}
                <div class="card is-hidden mt-4" id="citation-card">
                    <div class="card-content">
                        {{-- Lista kuratora --}}
                        <p id="curator-list" class="mb-3 has-text-weight-semibold"></p>

                        {{-- Generisani citat --}}
                        <div class="box has-background-white-bis p-3">
                            <p style="font-family: monospace; font-size: 0.95em;" id="generated-citation"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

{{-- JAVASCRIPT LOGIKA ZA SELECT2 I AJAX --}}
@push('scripts')
<script>
    $(document).ready(function() {
        const citationPrompt = $('#citation-prompt');
        const citationCard = $('#citation-card');
        const curatorListElement = $('#curator-list');
        const generatedCitationElement = $('#generated-citation');
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // 1. INICIJALIZACIJA SELECT2 ZA PRETRAGU TAKSONA
        $('.select2-taxon-search').select2({
            placeholder: "{{ __('about.type_taxon_name') }}",
            allowClear: true,
            theme: 'classic',
            ajax: {
                // Ruta za pretragu taksona
                url: "{{ route('api.taxa.index') }}",
                dataType: 'json',
                delay: 500,
                data: function(params) {
                    return {
                        name: params.term,
                        page: params.page || 1,
                        limit: 20
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;

                    return {
                        results: $.map(data.data, function(taxon) {
                            return {
                                id: taxon.id,
                                text: taxon.name + (taxon.native_name ? ` (${taxon.native_name})` : ""),
                            };
                        }),
                        pagination: {
                            more: data.meta.current_page < data.meta.last_page
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 3,
            templateSelection: function(taxon) {
                return taxon.text;
            }
        });

        // 2. OBRADA IZABRANOG TAKSONA
        $('#taxon-search').on('select2:select', function(e) {
            const selectedTaxon = e.params.data;
            fetchCitationData(selectedTaxon.id, selectedTaxon.text);
        });

        $('#taxon-search').on('select2:unselect', function(e) {
            citationCard.addClass('is-hidden');
            citationPrompt.removeClass('is-hidden');
            curatorListElement.empty();
            generatedCitationElement.empty();
        });


        // 3. AJAX POZIV ZA DOHVAT KURATORA I CITATA
        function fetchCitationData(taxonId, taxonName) {
            citationPrompt.addClass('is-hidden');
            citationCard.removeClass('is-hidden');

            curatorListElement.html('Učitavanje kuratora za <b>' + taxonName + '</b>...');
            generatedCitationElement.text('Generisanje citata...');

            $.ajax({
                url: "{{ route('api.citation.curators') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    taxon_id: taxonId
                },
                success: function(data) {
                    if (data.citation) {
                        const editorsText = data.editors || 'Nema dodeljenih kuratora.';
                        curatorListElement.html('Kuratori za <b>' + data.taxonName + '</b>: ' + editorsText);
                        generatedCitationElement.text(data.citation);
                    } else {
                        curatorListElement.text('Nema dostupnih kuratora ili greška.');
                        generatedCitationElement.text('Pokušajte ponovo.');
                    }
                },
                error: function(xhr) {
                    console.error('Greška pri dohvatu citata:', xhr.responseText);
                    curatorListElement.text('Došlo je do greške u komunikaciji sa serverom.');
                    generatedCitationElement.text('Pokušajte ponovo.');
                }
            });
        }
    });
</script>
@endpush