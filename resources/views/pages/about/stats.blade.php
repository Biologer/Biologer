@extends('layouts.main', ['title' => __('navigation.stats')])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>{{ __('navigation.stats') }}</h1>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.top_10_users') }}</caption>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('pages.stats.user') }}</th>
                        <th>{{ __('pages.stats.observations_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topUsers as $index => $user)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->field_observations_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.top_10_curators') }}</caption>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('pages.stats.curator') }}</th>
                        <th>{{ __('pages.stats.identifications_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($topCurators as $index => $curator)
                        <tr>
                            <th>{{ $index + 1 }}</th>
                            <td>{{ $curator->full_name }}</td>
                            <td>{{ $curator->field_observations_identified_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.observations_count_by_group') }}</caption>
                <thead>
                    <tr>
                        <th>{{ __('pages.stats.group') }}</th>
                        <th>{{ __('pages.stats.observations_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groups as $rootGroup)
                        <tr class="has-background-light">
                            <td colspan="2">{{ $rootGroup->name_with_fallback }}</td>
                        </tr>

                        @foreach ($rootGroup->groups as $group)
                            <tr>
                                <td>{{ $group->name_with_fallback }}</td>
                                <td>{{ $group->observations_count }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <table class="table">
                <caption class="is-size-4">{{ __('pages.stats.observations_count_by_year') }}</caption>
                <thead>
                    <tr>
                        <th>{{ __('pages.stats.year') }}</th>
                        <th>{{ __('pages.stats.observations_count') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($observationsByYear as $row)
                        <tr>
                            <td>{{ $row->year }}</td>
                            <td>{{ $row->observations_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
