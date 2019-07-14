@extends('layouts.dashboard', ['title' => __('navigation.preferences.notifications')])

@section('sidebar', Menu::preferencesSidebar())

@section('content')

@if(session('success'))
    <article class="message shadow is-success">
        <div class="message-body">
            {{ session('success') }}
        </div>
    </article>
@endif

@if($errors->isNotEmpty())
    <article class="message shadow is-danger">
        <div class="message-body">
            {{ $errors->first() }}
        </div>
    </article>
@endif

<div class="box">
    <h2 class="is-size-4">{{ __('navigation.preferences.notifications_preferences') }}</h2>

    <hr>

    <form action="{{ route('preferences.notifications') }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}

        <table class="table is-fullwidth">
            <thead>
                <tr>
                    <th>{{ __('labels.preferences.notifications.notification') }}</th>
                    <th>{{ __('labels.preferences.notifications.inapp') }}</th>
                    <th>{{ __('labels.preferences.notifications.mail') }}</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th>{{ __('labels.preferences.notifications.field_observation_approved') }}</th>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_approved[database]"
                                value="1"
                                {{ old('field_observation_approved.database', $user->settings()->get('notifications.field_observation_approved.database')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_approved[mail]"
                                value="1"
                                {{ old('field_observation_approved.mail', $user->settings()->get('notifications.field_observation_approved.mail')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th>{{ __('labels.preferences.notifications.field_observation_edited') }}</th>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_edited[database]"
                                value="1"
                                {{ old('field_observation_edited.database', $user->settings()->get('notifications.field_observation_edited.database')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_edited[mail]"
                                value="1"
                                {{ old('field_observation_edited.mail', $user->settings()->get('notifications.field_observation_edited.mail')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th>{{ __('labels.preferences.notifications.field_observation_moved_to_pending') }}</th>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_moved_to_pending[database]"
                                value="1"
                                {{ old('field_observation_moved_to_pending.database', $user->settings()->get('notifications.field_observation_moved_to_pending.database')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_moved_to_pending[mail]"
                                value="1"
                                {{ old('field_observation_moved_to_pending.mail', $user->settings()->get('notifications.field_observation_moved_to_pending.mail')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>
                </tr>

                <tr>
                    <th>{{ __('labels.preferences.notifications.field_observation_marked_unidentifiable') }}</th>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_marked_unidentifiable[database]"
                                value="1"
                                {{ old('field_observation_marked_unidentifiable.database', $user->settings()->get('notifications.field_observation_marked_unidentifiable.database')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>

                    <td>
                        <label class="b-checkbox checkbox">
                            <input
                                type="checkbox"
                                name="field_observation_marked_unidentifiable[mail]"
                                value="1"
                                {{ old('field_observation_marked_unidentifiable.mail', $user->settings()->get('notifications.field_observation_marked_unidentifiable.mail')) ? 'checked' : '' }}
                            >
                            <span class="check"></span>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="field mt-8">
            <button type="submit" class="button is-primary">{{ __('buttons.save') }}</button>
        </div>
    </form>
</div>
@endsection

@section('breadcrumbs')
<div class="breadcrumb" aria-label="breadcrumbs">
    <ul>
        <li><a href="{{ route('contributor.index') }}">{{ __('navigation.dashboard') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.index') }}</a></li>
        <li class="is-active"><a>{{ __('navigation.preferences.notifications_preferences') }}</a></li>
    </ul>
</div>
@endsection
