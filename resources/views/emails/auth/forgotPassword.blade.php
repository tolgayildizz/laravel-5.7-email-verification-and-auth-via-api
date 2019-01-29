@component('mail::message')
    Password renewal link
    @component('mail::button', ['url' => $resetUrlWithToken, 'color' => 'success'])
        View Order
    @endcomponent

    Thanks,
    {{ config('app.name') }}
@endcomponent

