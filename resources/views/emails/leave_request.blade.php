@component('mail::message')
# Leave Request

<p>{{ $message }}</p>

<p><b>Leave Type:</b> {{$leave->leave_types->name}}<p>
<p><b>Date:</b> {{ \Carbon\Carbon::parse($leave->from_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($leave->to_date)->format('d M, Y') }}<p>
<p><b>Leave Days:</b> {{$leave->no_of_days}}<p>
<p><b>Leave Slot:</b> {{$leave->leave_slots->name}}<p>
<p><b>Requested By:</b> {{$leave->user->name_with_code}}<p>
<p><b>Status:</b> {!! $status !!}<p>
<p><b>Reason:</b> {{$leave->reason}}<p>

@component('mail::button', ['url' => route('leaves.index')])
View
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
