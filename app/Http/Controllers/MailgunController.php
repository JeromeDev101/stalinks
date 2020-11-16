<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Messages;
use App\Http\Resources\ShowMessage;
use App\Http\Resources\MessageRecipient;
use App\Http\Resources\MessageSent;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

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
            'email'     => 'required|max:100',
            'title'     => 'required',
            'content'   => 'required',
            'sender'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        // $request['body-plain'] = $request->content;
        
        //  $data = [
        //     'sender'            => $request->sender,
        //     'subject'           => $request->title,
        //     'body'              => json_encode($request->only('body-plain')),
        //     'attachment'        => '',
        //     'from_mail'         => $request->sender,
        //     'date'              => '',
        //     'message_id'        => '',
        //     'received'          => $request->email,
        //     'references_mail'   => '',
        //     'label_id'          => 0,
        //     'is_starred'        => 0,
        //     'deleted_at'        => null,
        //     'created_at'        => date('Y-m-d'),
        //     'updated_at'        => date('Y-m-d'),


        // ];

       
        // DB::table('replies')->insert($data);

       


    	$this->mg->messages()->send('tools.stalinks.com', [
		  'from'    => Auth::user()->work_mail,
		  'to'      => $request->email,
		  'subject' => $request->title,
          'text'    => $request->content,
          'o:tracking-opens' => 'yes',
          'o:tracking-clicks' => 'yes'
          
        ]);
        
        Reply::create([
            'sender' => Auth::user()->work_mail,
            'subject' => $request->title,
            'is_sent' => 1,
            'label_id' => 0,
            'received' => $request->email,
            'body' => $request->content,
            'from_mail' => Auth::user()->work_mail,
            'attachment' => '',
            'date' => '',
            'message_id' => '',
            'references_mail' => '',
        ]);

          
       


		return response()->json(['message'=> 'Email sent Successfully!','status'=> 200], 200);

    }

    public function retrieve_all()
    {
    	$aw = $this->mg->events()->get('tools.stalinks.com');

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
        //dd($message);
    	return response()->json( new ShowMessage($message) );
    }

    public function recipient_filter(Request $request)
    {
    	// $validator = Validator::make($request->all(), [
        //     'email' => 'required|max:100'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->messages());
        // }

        $inbox = Reply::orderBy('id', 'desc')->where('is_sent', 0);

        if (isset($request->email) && $request->email != ''){
            $inbox = $inbox->where('received', $request->email);
        }

        if (isset($request->param) && $request->param != ''){
            switch ($request->param) {
                case 'Inbox':
                    $inbox = $inbox;
                    break;
                case 'Sent':
                    $inbox = $inbox->OrWhere('is_sent', 1);
                    break;
                case 'Trash':
                    // $inbox = $inbox->withTrashed();
                    $inbox = $inbox;
                    break;
                case 'Starred':
                    $inbox = $inbox->where('is_starred', $request->email);
                    break;
                default:
                    $inbox = $inbox;
              }
        }

        $inbox = $inbox->get();

        // $inbox = DB::table('replies')->where('received', $request->email)->get();

        //$aw = $this->mg->events()->get('tools.stalinks.com');
        return response()->json(['count'=> count($inbox), 'inbox'=> $inbox]);

        //return response()->json( new MessageRecipient( collect($aw->getItems()), $request->email) );


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

    // $expression = "catch_all()";
    // $actions = ['store(notify="https://tools.stalinks.com/api/mail/post-reply")'];
    // $description = 'Test route';
        dd("aw");
    $expression = "match_recipient('moravel752@gmail.com')";
$actions = ["forward('https://tools.stalinks.com/api/mail/post-reply')"];
$description = 'Test route';

    $this->mg->routes()->create($expression, $actions, $description);
    dd("route 51");

    //  $expression = "catch_all()";
    //  $actions = ['forward("https://tools.stalinks.com/api/mail/post-reply")'];
    //  $description = 'Test';

     

    // $this->mg->routes()->create($expression, $actions, $description);
    // dd("route 2");

      $we =   $this->mg->routes()->index();
      dd($we);

    //   $this->mg->routes()->delete('5fa4abdcd91a661f5e4f2dcb');
    //  $this->mg->routes()->delete('5fa4a6aacd2ab582a5f03fdf');
    //   dd("route delted");


     $aw = $this->mg->events()->get('tools.stalinks.com');
     
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
        //return response()->json($request->all());

        DB::table('test_replies')->insert(['alldata' => json_encode($request->all())]);   
        $data = [
            'sender'            => $request->sender,
            'subject'           => $request->subject,
            'body'              => json_encode($request->only('body-plain')),
            'attachment'        => '',
            'from_mail'         => $request->from,
            'date'              => '',
            'message_id'        => '',
            'received'          => $request->recipient,
            'references_mail'   => '',
            'label_id'          => 0,
            'is_starred'        => 0,
            'deleted_at'        => null,
            'created_at'        => date('Y-m-d'),
            'updated_at'        => date('Y-m-d'),


        ];

       
        DB::table('replies')->insert($data);

        return response()->json($request->all()); 
        
    }

    public function sent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $aw = $this->mg->events()->get('tools.stalinks.com');

       
        return response()->json( new MessageSent( collect($aw->getItems()), $request->email) );
    }

    public function starred(Request $request){
        if (is_array($request->id)) {
            foreach($request->id as $data) {
                $record = json_decode($data);
                $reply = Reply::findOrFail($record->id);
                $reply->update(['is_starred' => 1]);
            }
        }else{
            $reply = Reply::findOrFail($request->id);
            $reply->update([
                'is_starred' => $request->is_starred == 0 ? 1:0,
            ]);
        }

        return response()->json(['succsss' => true],200);
    }

    public function deleted(Request $request)
    {
        $inbox = DB::table('replies')->where('received', $request->email)->where('deleted_at','!=', null)->get();
        return response()->json(['count'=> count($inbox),'deleted'=> $inbox]);
    }
}
