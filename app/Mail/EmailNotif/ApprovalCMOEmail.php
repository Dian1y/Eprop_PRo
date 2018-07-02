<?php

namespace App\Mail\EmailNotif;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\WorkflowEmail\UserApproverEvent;

class ApprovalCMOEmail extends Mailable
{
    use Queueable, SerializesModels;

    Public $wfApprover;

    Public $file_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($wfApprover)
    {
        $this->wfApprover = $wfApprover;        
        $file_name =  $wfApprover->attach_file;
        $appsType = $wfApprover->message_type;
        
        $this->file_name = $file_name;
        $this->message_type = $message_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($this->wfApprover);
        if ($this->message_type == 'CMO_WF_APPROVAL') {
                return $this->markdown('email.notif.cmoapproval')->attach($this->file_name)->with('wfApprover', $this->wfApprover);
        } else {
                return $this->markdown('email.notif.eprop_approval')->with('wfApprover', $this->wfApprover);
        }   
    }
}
