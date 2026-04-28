@extends('layouts.main', ['title' => __('navigation.stats')])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>{{ __('navigation.stats') }}</h1>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.top_10_users') }}</caption>
                <thead>
                    <tr>
                        <th style="width:10%">#</th>
                        <th style="width:45%">{{ __('pages.stats.user') }}</th>
                        <th style="width:45%">{{ __('pages.stats.observations_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topUsers as $index => $user)
                        <tr>
                            <th style="width:10%">{{ $index + 1 }}</th>
                            <td style="width:45%">{{ $user->full_name }}</td>
                            <td style="width:45%">{{ $user->field_observations_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.top_10_curators') }}</caption>
                <thead>
                    <tr>
                        <th style="width:10%">#</th>
                        <th style="width:45%">{{ __('pages.stats.curator') }}</th>
                        <th style="width:45%">{{ __('pages.stats.identifications_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topCurators as $index => $curator)
                        <tr>
                            <th style="width:10%">{{ $index + 1 }}</th>
                            <td style="width:45%">{{ $curator->full_name }}</td>
                            <td style="width:45%">{{ $curator->field_observations_identified_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.observations_count_by_group') }}</caption>
                <thead>
                    <tr>
                        <th style="width:55%">{{ __('pages.stats.group') }}</th>
                        <th style="width:45%">{{ __('pages.stats.observations_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $rootGroup)
                        <tr class="has-background-light">
                            <td colspan="2" style="width:100%">{{ $rootGroup->name_with_fallback }}</td>
                        </tr>

                        @foreach ($rootGroup->groups as $group)
                            <tr>
                                <td style="width:55%">{{ $group->name_with_fallback }}</td>
                                <td style="width:45%">{{ $group->observations_count }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.observations_count_by_year') }}</caption>
                <thead>
                    <tr>
                        <th style="width:55%">{{ __('pages.stats.year') }}</th>
                        <th style="width:45%">{{ __('pages.stats.observations_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($observationsByYear as $row)
                        <tr>
                            <td style="width:55%">{{ $row->year }}</td>
                            <td style="width:45%">{{ $row->observations_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
