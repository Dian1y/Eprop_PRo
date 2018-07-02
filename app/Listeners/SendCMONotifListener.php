<?php

namespace App\Listeners;

use App\Events\SendCMONotif;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Mail\EmailNotif\ApprovedCMONotif;

class SendCMONotifListener
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
     * @param  SendCMONotif  $event
     * @return void
     */
    public function handle(SendCMONotif $event)
    {

        
        foreach ($event->CMOApproved as $key => $value) {
            $recipient_email = $value['mail_to'];
            $cc = $value->mail_cc;
        }
        
       
        Mail::to($recipient_email)
              ->cc($cc)  
              ->send(new ApprovalCMOEmail($event->CMOApproved));
    }
}
