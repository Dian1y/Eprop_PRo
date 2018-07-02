<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event\mail\sendEmailAppovalCM

class TestEmail extends Controller
{
	
	public function showTestMail() {  

		return view('mails.test_mail');
	}    

	public function testSendMail() {

		event(new SendEmailAppovalCMO())
	}

}
