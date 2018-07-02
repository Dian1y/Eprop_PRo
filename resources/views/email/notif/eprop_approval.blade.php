@component('mail::message')
##Eprop No. {{ $wfApprover->message_name }}
##Title : {{$wfApprover->message_name }}


Membutuhkan Approval dari Anda.


@component('mail::button', ['url' => route('cmo.approve', ['id'=>$wfApprover->id, 'wfkeyid'=>$wfApprover->wf_key_id, 'webApprv'=>'N'])])
Approve
@endcomponent


@component('mail::button', ['url' => route('cmo.reject',  ['id'=>$wfApprover->id, 'wfkeyid'=>$wfApprover->wf_key_id, 'webApprv'=>'N'])])
Reject
@endcomponent


Thanks, </br>

{{ config('app.name') }}
@endcomponent
