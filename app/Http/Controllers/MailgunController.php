<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\Messages;
use App\Http\Resources\ShowMessage;

class MailgunController extends Controller
{
	private $mail;

	public function __construct()
	{
		$this->mail = Mailgun::create(config('gun.mail_api'));
	}
    
    public function send()
    {
    	

    	$this->mail->messages()->send('headzupnegor.com', [
		  'from'    => 'postmaster@headzupnegor.com',
		  'to'      => 'morley.marketingcrossmedia@gmail.com',
		  'subject' => 'neg',
		  'text'    => 'neg beg'
		]);

		return response()->json(['message'=> 'Email sent Successfully!','status'=> 200], 200);

    }

    public function retrieve_all()
    {
    	$aw = $this->mail->events()->get('headzupnegor.com');

    	return response()->json( new Messages(collect($aw->getItems())) );
    }

    public function view_message(Request $request)
    {
    	
    	$validator = Validator::make($request->all(), [
            'url' => 'required|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

    	$message = $this->mail->messages()->show($request->url);

    	return response()->json( new ShowMessage($message) );
    }
}
