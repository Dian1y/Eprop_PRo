@component('mail::message')
##CMO Order No. {{ $CMOApproved->message_name }}

{{ $CMOApproved->customer_ship_name }}

Periode : date('M Y', strtotime({{ $CMOApproved->periode_cmo }}));


Telah Di Approved Oleh ASDH : {{ $CMOApproved->approver }}

Thanks, </br>

{{ config('app.name') }}
@endcomponent
from_date