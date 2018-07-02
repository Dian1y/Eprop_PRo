<?php

namespace App\Mail\EmailNotif;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SendCMONotif;

class ApprovedCMONotif extends Mailable
{
    use Queueable, SerializesModels;

    Public $CMOApproved;

    Public $file_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($CMOApproved)
    {
        $this->CMOApproved = $CMOApproved;        
        $file_name =  $CMOApproved->attach_file;
        
        $this->file_name = $file_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.notif.approvedCMO')->attach($this->file_name)->with('CMOApproved', $this->CMOApproved);
    }
}
