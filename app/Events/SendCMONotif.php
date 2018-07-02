<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class SendCMONotif
{
    use Dispatchable, SerializesModels;

    public $CMOApproved;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($CMOApproved)
    {
        $this->CMOApproved = $CMOApproved;
    }

}
