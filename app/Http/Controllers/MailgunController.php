<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\Messages;
use App\Http\Resources\ShowMessage;
use App\Http\Resources\MessageRecipient;

class MailgunController extends Controller
{
	private $mail;

	public function __construct()
	{
		$this->mail = Mailgun::create(config('gun.mail_api'));
	}
    
    public function send(Request $request)
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

    public function recipient_filter(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'email' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $aw = $this->mail->events()->get('headzupnegor.com');

        return response()->json( new MessageRecipient( collect($aw->getItems()), $request->email) );


    }

    public function status(Request $request)
    {
    	// $validator = Validator::make($request->all(), [
     //        'domain' => 'required|max:100'
     //    ]);

     //    if ($validator->fails()) {
     //        return response()->json($validator->messages());
     //    }

       
    	

        $we = $this->mail->events()->get('headzupnegor.com');


        

       	dd($we->getItems());
        dd(get_class_methods($we));

    	return response()->json($we);
    }
}
