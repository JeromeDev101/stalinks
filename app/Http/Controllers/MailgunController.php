<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Messages;
use App\Http\Resources\ShowMessage;
use App\Http\Resources\MessageRecipient;

class MailgunController extends Controller
{
	private $mg;

	public function __construct()
	{
		$this->mg = Mailgun::create(config('gun.mail_api'));
	}
    
    public function send(Request $request)
    {
    	
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100',
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

    
    	$this->mg->messages()->send('stalinks.com', [
		  'from'    => 'jessica-buyer@stalinks.com',
		  'to'      => $request->email,
		  'subject' => $request->title,
          'text'    => $request->content,
          'o:tracking-opens' => 'yes',
          'o:tracking-clicks' => 'yes'
          
		]);

		return response()->json(['message'=> 'Email sent Successfully!','status'=> 200], 200);

    }

    public function retrieve_all()
    {
    	$aw = $this->mg->events()->get('stalinks.com');

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

    	$message = $this->mg->messages()->show($request->url);

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

        $aw = $this->mg->events()->get('stalinks.com');

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

    //  $we = $this->mg->domains()->index();
    //  dd($we);

    //  $expression = "catch_all()";
    //  $actions = ["store()"];
    //  $description = 'Test';

    // $this->mg->routes()->create($expression, $actions, $description);
    // dd("route");

    //   $we =   $this->mg->routes()->index();
    //   dd($we);

    //   $this->mg->routes()->delete('5fa1f378d7b4bcbfe9beb4bc');
    //   $this->mg->routes()->delete('5fa1f08fdc89bb01a75b9235');
    //   dd("route delted");


     $aw = $this->mg->events()->get('stalinks.com');
     
     foreach($aw->getItems() as $kwe)
     {
         echo $kwe->getEvent().'<br>';
        //  if($kwe->getEvent() == "opened"){
        //      dd("aw");
        //  }
         
         //dd($kwe->getRecipient());
     
         //var_dump($kwe);

        //  foreach($kwe->getStorage() as $me)
        //  {
        //      echo $me.'<br>';
        //  }
     }

    //  dd("wala");
    // 	$aw = $this->mail->tags()->stats('headzupnegor.com', 'Tag1');
    //     dd($aw);

    //     $we = $this->mail->events()->get('headzupnegor.com');


        

    //    	dd($we->getItems());
    //     dd(get_class_methods($we));

    // 	return response()->json($we);
    }

    public function post_reply(Request $request)
    {
       // DB::table('replies')->insert(['alldata'=> $request->all()]);
        return response()->json($request->all());
    }
}
