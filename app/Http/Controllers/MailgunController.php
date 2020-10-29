<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mailgun\Mailgun;

class MailgunController extends Controller
{
	
    
    public function send()
    {
    	$mg = Mailgun::create(config('gun.mail_api'));

    	$mg->messages()->send('headzupnegor.com', [
		  'from'    => 'postmaster@headzupnegor.com',
		  'to'      => 'morley.marketingcrossmedia@gmail.com',
		  'subject' => 'neg',
		  'text'    => 'neg beg'
		]);

		return response()->json(['message'=> 'Email sent Successfully!','status'=> 200], 200);

    }

    public function retrieve()
    {

    }
}
