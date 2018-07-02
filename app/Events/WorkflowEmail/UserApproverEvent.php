<?php

namespace App\Events\WorkflowEmail;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserApproverEvent
{
    use Dispatchable, SerializesModels;

    public $wfApprover;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($wfApprover)
    { 
        $this->wfApprover = $wfApprover;
    }

}
