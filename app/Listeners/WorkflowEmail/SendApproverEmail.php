<?php

namespace App\Listeners\WorkflowEmail;

use App\Events\WorkflowEmail\UserApproverEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\EmailNotif\ApprovalCMOEmail;

class SendApproverEmail
{
 
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SendApproverEmail  $event
     * @return void
     */
    public function handle(UserApproverEvent $event)
    {

        $recipient_email = $event->wfApprover->recipient_email;
       
        Mail::to($recipient_email)->send(new ApprovalCMOEmail($event->wfApprover));
    }
}
