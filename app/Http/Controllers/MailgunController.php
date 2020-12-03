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
        // dd($request->attachment);
        // if(isset($request->attachment1))
        // {
        //     $list = array($request->attachment1, $request->attachment2);

        //     $list_attach = array();

        //     foreach ($list as $key => $value) {
        //         $list_attach[$key]['filePath'] = $value->getRealPath();
        //         $list_attach[$key]['filename'] = $value->getClientOriginalName();

        //     }

            

           
        // }else {
        //     $list_attach = '';
        // }

        if($request->attachment == "undefined" )
        {
            $atth = null;
        }else{
            $atth = [
                array('filePath'=>$request->attachment->getRealPath(),'filename'=>$request->attachment->getClientOriginalName()),
            ];
            
        }

        $request->validate([
            'email'     => 'required',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        

        // if ($validator->fails()) {
        //     return response()->json($validator->messages(),422);
        // }

        $email_to = $request->email;

        if (strpos($request->email, '|') !== false) {
            $email_to = str_replace("|",",",$request->email);
        }

        $myArray = explode(',', $email_to);
       
        //add current email another associalte array
        $aw = [];

        foreach ($myArray as $key => $value) {
            $kwe = array($value => ["first"=> $request->title, "id" => $key+1]);
            array_push($aw, $kwe);
        }

        //convert to object
        $object = (object)$aw;

        //arrange all list of emails into string to be ready as one element array_merge
        $list_emails = array();
        foreach ($myArray as $key => $value) {
        $list_emails[$key] = $value;

        }

        $str = implode (", ", $list_emails);
        
       


       
    	$sender = $this->mg->messages()->send('stalinks.com', [
		    'from'                  => Auth::user()->work_mail,
		    'to'                    => array($str),
            'bcc'                   => isset($request->cc) && $request->cc != "" ? $request->cc : 'moravel752@gmail.com',
		    'subject'               => $request->title,
            'text'                  => $request->content,
            'recipient-variables'   => json_encode($object),
            'attachment'            => $atth,
            'o:tag'                 => array('test1'),
            'o:tracking'            => 'yes',
            'o:tracking-opens'      => 'yes',
            'o:tracking-clicks'     => 'yes',
        ]);
        
        $attac_object = '';
        if($request->attachment != "undefined" )
        {
            $attach = time().'.'.$request->attachment->getClientOriginalExtension();
            $request->attachment->move(public_path('/attachment'), $attach);

            $attac_object = [
                'url'           => url('/attachment/'.$attach),
                'size'          => \File::size(public_path('/attachment/'), $attach), 
                'type'          => $request->attachment->getClientOriginalExtension(),
                'filename'      => $attach,
                'display_name'  => $request->attachment->getClientOriginalName()
            ];
        }

        $input['body-plain'] = $request->content;
        
        Reply::create([
            'sender'            => Auth::user()->work_mail,
            'subject'           => $request->title,
            'is_sent'           => 1,
            'is_viewed'         => 1,
            'label_id'          => 0,
            'received'          => $request->email,
            'body'              => json_encode($input),
            'from_mail'         => Auth::user()->work_mail,
            'attachment'        => $attac_object == '' ? '' : json_encode($attac_object),
            'date'              => '',
            'message_id'        => '',
            'references_mail'   => '',
        ]);


        
        

          
       


		return response()->json(['success'=> true, 'message'=> $sender], 200);

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
            'email' => 'required|max:100',
            'param' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }


        $inbox = Reply::select('replies.*', 'labels.name as label_name', 'labels.color as label_color')
                    ->leftJoin('labels', 'replies.label_id' ,'=', 'labels.id')
                    ->orderBy('id', 'desc');

        if (isset($request->search_mail) && $request->search_mail != ''){
            $inbox = $inbox->orWhere('replies.received', 'like','%'.$request->search_mail.'%')
                            ->orWhere('replies.subject', 'like','%'.$request->search_mail.'%')
                            ->orWhere('replies.body', 'like','%'.$request->search_mail.'%')
                            ->orWhere('replies.from_mail', 'like','%'.$request->search_mail.'%')
                            ->orWhere('replies.sender', 'like','%'.$request->search_mail.'%');
        }

        if (isset($request->label_id) && $request->label_id != ''){
            $inbox = $inbox->orWhere('replies.label_id', $request->label_id);
        }

        if (isset($request->param) && $request->param != ''){
            switch ($request->param) {
                case 'Inbox':
                    $inbox = $inbox->where('replies.received', $request->email)->where('replies.is_sent', 0)->whereNull('replies.deleted_at');
                    break;
                case 'Sent':
                    $inbox = $inbox->where('replies.sender', $request->email)->where('replies.is_sent', 1);
                    break;
                case 'Trash':
                    // $inbox = $inbox->withTrashed();
                    $inbox = $inbox->where('replies.sender', $request->email)->orWhere('replies.received', $request->email)->whereNotNull('replies.deleted_at');
                    break;
                case 'Starred':
                    $inbox = $inbox->where('replies.sender', $request->email)->orWhere('replies.received', $request->email)->where('replies.is_starred', 1);
                    break;
                default:
                    $inbox = $inbox;
              }
        }

        $inbox = $inbox->get();
        $cnt = Reply::where('is_viewed', 0)->where('is_sent', 0)->whereNull('deleted_at')->count();

        return response()->json(['count'=> $cnt, 'inbox'=> $inbox]);

    }

    public function status(Request $request)
    {
        dd("tae");
    	// $validator = Validator::make($request->all(), [
     //        'domain' => 'required|max:100'
     //    ]);

     //    if ($validator->fails()) {
     //        return response()->json($validator->messages());
     //    }

    //  $we = $this->mg->domains()->index();
    //  dd($we);

    // $expression = "catch_all()";
    // $actions = ['store(notify="https://stalinks.com/api/mail/post-reply")'];
    // $description = 'Test route';
        dd("aw");
    $expression = "match_recipient('moravel752@gmail.com')";
$actions = ["forward('https://stalinks.com/api/mail/post-reply')"];
$description = 'Test route';

    $this->mg->routes()->create($expression, $actions, $description);
    dd("route 51");

    //  $expression = "catch_all()";
    //  $actions = ['forward("https://stalinks.com/api/mail/post-reply")'];
    //  $description = 'Test';

     

    // $this->mg->routes()->create($expression, $actions, $description);
    // dd("route 2");

      $we =   $this->mg->routes()->index();
      dd($we);

    //   $this->mg->routes()->delete('5fa4abdcd91a661f5e4f2dcb');
    //  $this->mg->routes()->delete('5fa4a6aacd2ab582a5f03fdf');
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
       
        //return response()->json($request->all());
       
       // dd(json_decode($request->attachments)[0]);
        //dd("exit");
        $attch_obj = json_decode($request->attachments)[0];
        DB::table('test_replies')->insert(['alldata' => json_encode($request->all())]);   
        $data = [
            'sender'            => $request->sender,
            'subject'           => $request->subject,
            'body'              => json_encode($request->only('body-plain')),
            'attachment'        => isset($request->attachments) ? json_encode($attch_obj) : '',
            'from_mail'         => $request->from,
            'date'              => '',
            'message_id'        => '',
            'received'          => $request->recipient,
            'references_mail'   => '',
            'label_id'          => 0,
            'is_starred'        => 0,
            'deleted_at'        => null,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),


        ];

       
        DB::table('replies')->insert($data);

        return response()->json($request->all()); 
        
    }

    

    public function starred(Request $request){
        // dd($request->all());
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

    public function setViewMessage(Request $request) {
        $inbox = Reply::findOrFail($request->id);
        $inbox->update(['is_viewed' => 1]);

        return response()->json(['success' => true],200);
    }

    public function labeling(Request $request) {
        if (is_array($request->ids)) {
            foreach ($request->ids as $ids) {
                $inbox = Reply::findOrFail($ids);
                $inbox->update($request->only('label_id'));
            }
        }

        return response()->json(['success' => true],200);
    }

    public function deleteMessage(Request $request) {
        if (is_array($request->id)) {
            foreach($request->id as $id) {
                $inbox = Reply::findOrFail($id);
                $inbox->update(['deleted_at' => date('Y-m-d')]);
            }
        }else{
            $inbox = Reply::findOrFail($request->id);
            $inbox->update(['deleted_at' => date('Y-m-d')]);
        }

        return response()->json(['success' => true],200);
    }

    public function get_reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $replies = DB::table('replies')->where('sender', Auth::user()->work_mail)->where('received',$request->email)->orderBy('created_at','DESC')->get();

        return response()->json($replies);
    }

    public function show_attachment(Request $request)
    {
        return $this->mg->attachment()->show($request->url);
        
        
    }

    public function check_domain(Request $request)
    {
        try{
            $client = new \GuzzleHttp\Client();
            $request = $client->get($request->domain);
            $response = $request;
           
            return response()->json(['status'=> $response->getStatusCode(), 'message'=>$response->getReasonPhrase()]);
        }catch(\Exception $e){
            return response()->json(['status'=> 401,'message'=> $e->getHandlerContext()['error']]);
        }
    }
}
