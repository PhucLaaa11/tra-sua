@component('mail::message')
# Hello {{ $user->name }} 👋

Thank you for registering at **Boba Shop** 🍹.  
We hope you have a wonderful experience with our product management system.

@component('mail::button', ['url' => url('/')])
Get Started
@endcomponent

Best regards,<br>
{{ config('app.name') }}
@endcomponent