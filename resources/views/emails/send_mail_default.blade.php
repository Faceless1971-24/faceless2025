@component('mail::message')
# {{ $title ?? 'Introduction' }}

The body of your message.

{{-- A request of {{$leave_type}} from {{$from}} to {{$to}}, {{$leave_days}} days of {{$leave_slot}} leave added by {{$user_name}}. --}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
