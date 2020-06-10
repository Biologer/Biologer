@component('mail::message')
{{-- Greeting --}}
# {{ __('notifications.email.hello') }}

@component('mail::table')
| {{ __('navigation.notifications') }} |   |
| ------------------------------------ | - |
@foreach ($unreadNotifications as $notification)
| {{ $notification['message'] }} | [{{ $notification['actionText'] }}]({{ $notification['actionUrl'] }}) |
@endforeach
@endcomponent

@component('mail::button', ['url' => route('contributor.index'), 'color' => 'primary'])
{{ __('notifications.email.see') }}
@endcomponent

{{-- Salutation --}}
@lang('notifications.email.regards'), <br>{{ config('app.name') }}

{{-- Subcopy --}}
@slot('subcopy')
@lang('notifications.email.subcopy', [
    'actionText' => __('notifications.email.see'),
    'actionUrl' => route('contributor.index'),
])
@endslot
@endcomponent
