@component('mail::message')
# {{ $title }}

{!! $description !!}

**Departments:** {{ $departments }}

**Notice Date:** {{ $notice_date }}

{{-- @component('mail::button', ['url' => url('/notices')])
View Notice
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
