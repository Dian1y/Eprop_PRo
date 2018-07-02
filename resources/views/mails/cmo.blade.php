@component('mail::message')
# Hola,

You are receiving this email because we received a signup request for your this mail account.

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
