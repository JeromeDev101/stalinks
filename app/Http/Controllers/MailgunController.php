<?php

namespace App\Http\Controllers;

use App\Http\Resources\InboxResource;
use Illuminate\Http\Request;
use Mailgun\Mailgun;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\Messages;
use App\Http\Resources\ShowMessage;
use App\Http\Resources\MessageRecipient;
use App\Http\Resources\MessageSent;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;


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

        // check if email is a string or json object

        if(!is_array($request->email)){
            $request['email'] = json_decode($request->email, true) ?? $request->email;
        }

        $emailRule = [];

        if(is_array($request->email)) {
            $emailRule['email'] = 'required|array|max:10';
        }else{
            $emailRule['email'] = 'required';
        }

        $rules = [
            'email.*.text' => 'required|email',
            'title'     => 'required',
            'content'   => 'required',
        ];

        $request->validate(array_merge($emailRule, $rules));

        // if ($validator->fails()) {
        //     return response()->json($validator->messages(),422);
        // }

        $email_to = $request->email;

        if(is_array($email_to)) {
            $myArray = array_column($email_to, 'text');
        }else{
            if (strpos($request->email, '|') !== false) {
                $email_to = str_replace("|",",",$request->email);
            }

            $myArray = explode(',', $email_to);
        }

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

        $params = [
		    'from'                  => Auth::user()->work_mail,
		    'to'                    => array($str),
		    'subject'               => $request->title,
            'html'                  => "<div style='white-space: pre'>" . $request->content . "</div>",
            'recipient-variables'   => json_encode($object),
            'attachment'            => $atth,
            'o:tag'                 => array('test1'),
            'o:tracking'            => 'yes',
            'o:tracking-opens'      => 'yes',
            'o:tracking-clicks'     => 'yes',
        ];

        if(isset($request->cc) && $request->cc != ""){
            $params['bcc'] = $request->cc;
        }

        $sender = $this->mg->messages()->send(config('gun.mail_domain'), $params);

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
        $res = preg_replace("/[<->]/", "", $sender->getId());

        $sendEmail = Reply::create([
            'sender'            => Auth::user()->work_mail,
            'subject'           => $request->title,
            'is_sent'           => 1,
            'is_viewed'         => 1,
            'label_id'          => 0,
            'received'          => $str,
            'body'              => json_encode($input),
            'from_mail'         => Auth::user()->work_mail,
            'attachment'        => $attac_object == '' ? '' : json_encode($attac_object),
            'date'              => date('Y-m-d'),
            'message_id'        => $res,
            'references_mail'   => '',
            'status_code'       => 0,
            'message_status'    => '',
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
        //return response()->json(['inbox'=> Auth::user()->role_id]);

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

            $inbox = $inbox->where(function ($query) use ($request) {
                $query->orWhere('replies.received', 'like','%'.$request->search_mail.'%')
                    ->orWhere('replies.subject', 'like','%'.$request->search_mail.'%')
                    ->orWhere('replies.body', 'like','%'.$request->search_mail.'%')
                    ->orWhere('replies.from_mail', 'like','%'.$request->search_mail.'%')
                    ->orWhere('replies.sender', 'like','%'.$request->search_mail.'%');
            });

//            $inbox = $inbox->orWhere('replies.received', 'like','%'.$request->search_mail.'%')
//                            ->orWhere('replies.subject', 'like','%'.$request->search_mail.'%')
//                            ->orWhere('replies.body', 'like','%'.$request->search_mail.'%')
//                            ->orWhere('replies.from_mail', 'like','%'.$request->search_mail.'%')
//                            ->orWhere('replies.sender', 'like','%'.$request->search_mail.'%');
        }

        if (isset($request->label_id) && $request->label_id != ''){
            $inbox = $inbox->orWhere('replies.label_id', $request->label_id);
        }

        if (isset($request->param) && $request->param != ''){
            switch ($request->param) {
                case 'Inbox':
                    if($request->email == 'jess@stalinks.com' || $request->email == 'all'){
                        $inbox = $inbox->where('replies.is_sent', 0)->whereNull('replies.deleted_at');
                    }else{
                        $inbox = $inbox->where('replies.received', 'like', '%'.$request->email.'%')->where('replies.is_sent', 0)->whereNull('replies.deleted_at');
                    }

                    break;
                case 'Sent':
                     if($request->email == 'jess@stalinks.com' || $request->email == 'all'){
                        $inbox = $inbox->where('replies.is_sent', 1);
                     }else{
                        $inbox = $inbox->where('replies.sender', $request->email)->where('replies.is_sent', 1);
                     }

                    break;
                case 'Trash':
                    // $inbox = $inbox->withTrashed();
                // SELECT * FROM `replies` WHERE `deleted_at` != 1 AND `sender` = 'jess@stalinks.com' OR  `deleted_at` != 1 AND `received` = 'jess@stalinks.com'
                    if($request->email == 'jess@stalinks.com' || $request->email == 'all'){
                        $inbox = $inbox->where('replies.deleted_at','!=',1);
                    }else{
                        $inbox = $inbox->where('replies.deleted_at','!=',1)->where('replies.sender', $request->email)->orWhere('replies.deleted_at','!=',1)->where('replies.received', $request->email);
                    }

                    break;
                case 'Starred':
                     if($request->email == 'jess@stalinks.com' || $request->email == 'all'){
                        $inbox = $inbox->where('replies.is_starred', 1);
                     }else{
                        $inbox = $inbox->where('replies.is_starred', 1)->where('replies.sender', $request->email)->orWhere('replies.is_starred', 1)->where('replies.received', $request->email);
                     }

                    break;
                default:
                    $inbox = $inbox;
              }
        }

        $inbox = $inbox->paginate();

        $cnt = Reply::where('is_viewed', 0)->where('is_sent', 0)->whereNull('deleted_at')->count();

        return response()->json(['count'=> $cnt, 'inbox'=> $inbox]);

    }

    public function status_mail()
    {

        $arr = $this->mg->events()->get('stalinks.com', [
            'limit' => 300,
            'event' => 'delivered',
        ]);

        $arr_failed = $this->mg->events()->get('stalinks.com', [
            'limit' => 300,
            'event' => 'failed',
        ]);

        $delivered = [];
        $failed = [];

        //get failed
        foreach ($arr_failed->getItems() as $item) {
            if ($item->getEvent() === 'failed') {
                if (array_key_exists('message-id', $item->getMessage()['headers'])) {
                    $failed[] = $item->getMessage()['headers']['message-id'];
                }
            }
        }

        //update failed
        Reply::where('is_sent', 1)
            ->whereIn('message_id', $failed)->update([
                'status_code' => 552,
                'message_status' => 'FAILED'
            ]);

        //get delivered
        foreach ($arr->getItems() as $item) {
            if ($item->getDeliveryStatus()['code'] === 250) {
                if (array_key_exists('message-id', $item->getMessage()['headers'])) {
                    $delivered[] = $item->getMessage()['headers']['message-id'];
                }
            }
        }

        // update delivered
        Reply::where('is_sent', 1)
            ->whereIn('message_id', $delivered)->update([
                'status_code' => 250,
                'message_status' => 'OK'
            ]);

//        $aw = $this->mg->events()->get('stalinks.com');
//
//        //get all eventsitems
//        foreach ($aw->getItems() as $kwe) {
//            //all all message sent/received
//            foreach ( $kwe->getMessage() as $wa) {
//                //check if data is array some is not we need to make sure
//                if (is_array($wa)) {
//                    //check if mesage_id property exist before using it as condition
//                    if (array_key_exists("message-id",$wa)) {
//                        $pending = Reply::where('status_code',0)->get();
//
//                        foreach($pending as $get) {
//                            if($wa['message-id'] == $get->message_id)
//                            {
//                                $get->update([
//                                'status_code'       => $kwe->getDeliveryStatus()['code'],
//                                'message_status'    => $kwe->getDeliveryStatus()['message']
//                                ]);
//                            }
//                        }
//                    }
//                }
//            }
//        }
    }

    public function post_reply(Request $request)
    {

        $message_id = $request->only('Message-Id');
        $message_id = preg_replace("/[<>]/", "", $message_id['Message-Id']);
        $in_reply_to = null;
        $references = null;

        $aw = $this->mg->events()->get('stalinks.com');
        // $r_attachment = '';

        //get all eventsitems============
        foreach($aw->getItems() as $kwe)
        {

        //all all message sent/received==========
          foreach( $kwe->getMessage() as $wa)
          {
            //check if data is array some is not we need to make sure=======
            if(is_array($wa))
            {
                //check if mesage_id property exist before using it as condition=======
                if (array_key_exists("message-id",$wa))
                {


                    if($wa['message-id'] == $message_id)
                        {
                            //dd($kwe->getStorage()['url']);=========

                            $aw = $this->mg->messages()->show($kwe->getStorage()['url']);

                            $r_attachment = $aw->getAttachments();
                            //return response()->json( new ShowMessage($message) ); ============
                        }


                }
            }


          }

        }


        $input = $request->all();

        DB::table('test_replies')->insert(['alldata' => json_encode($input)]);

        // if( $request->has('attachments') )
        // {

        //    $attch_obj = json_decode($request->attachments)[0];
        // }

        // dd($r_attachment);

        if( isset($input['In-Reply-To']) && $input['In-Reply-To'] ){
            $in_reply_to = preg_replace("/[<>]/", "", $input['In-Reply-To']);
        }

        if( isset($input['References']) && $input['References'] ){
            $references = preg_replace("/[<>]/", "", $input['References']);
        }


        $data = [
            'sender'            => $request->sender,
            'subject'           => $request->subject,
            'body'              => json_encode($request->only('body-plain')),
            'attachment'        => json_encode($r_attachment),
            // 'attachment'        => '',
            'from_mail'         => $request->from,
            'date'              => '',
            'message_id'        => $message_id,
            'in_reply_to'       => $in_reply_to,
            'references'        => $references,
            'received'          => $request->recipient,
            'references_mail'   => '',
            'label_id'          => 0,
            'is_starred'        => 0,
            'deleted_at'        => null,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
            'status_code'       => 200,
            'message_status'    => 'message received'
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

    public function mail_logs(Request $request)
    {
        $mail_logs = DB::table('replies')
                        ->where('replies.is_sent',1)
                        ->select('replies.from_mail as from','replies.sender as user_mail','replies.received as to','replies.status_code as status','replies.created_at as date');

        if( isset($request->status) && $request->status != '') {
            $mail_logs = $mail_logs->where('replies.status_code', $request->status);
        }

        if( isset($request->user_email) && $request->user_email != '') {
            $mail_logs = $mail_logs->where('replies.sender', $request->user_email);
        }

        $mail_logs = $mail_logs->orderBy('created_at', 'desc')->get();


        $sent_today = Reply::where('is_sent',1)->where('status_code',250)->where('created_at', 'like', '%'.date('Y-m-d').'%')->count();

        $sent = Reply::where('is_sent',1)->where('status_code',250)->count();

        $total = Reply::where('is_sent',1)->count();

        $failed = Reply::where('is_sent',1)->where('status_code',552)->count();

        return response()->json([
            'logs'          => $mail_logs,
            'total_mail'    => $total,
            'sent'          => $sent,
            'sent_today'          => $sent_today,
            'failed'        => $failed,
        ]);
    }

    public function get_mail_list()
    {
        $user_email = User::select('work_mail')->distinct('work_mail')->where('work_mail', '!=', '')->orderBy('work_mail', 'asc')->get();
        return response()->json($user_email);
    }

    public function send_validation(Request $request)
    {
        $request->validate([
            'email'     => 'required',
            'title'     => 'required',
        ]);

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

        $list_emails = array();
        foreach ($myArray as $key => $value) {
        $list_emails[$key] = $value;

        }

        $str = implode (", ", $list_emails);

        $registration = Registration::where('email', $request->email)->first();

        if($registration->id) {
            $data = [
                'name' => $registration->name,
                'verification_code' => $registration->verification_code
            ];
        }


    	$sender = $this->mg->messages()->send('stalinks.com', [
		    'from'                  => 'support@stalinks.com',
		    'to'                    => array($str),
            // 'bcc'                   => 'lhabzter21@gmail.com',
		    'subject'               => $request->title,
            'text'                  => 'Email Validation',
            'html'                  => view('email', $data)->render(),
            'recipient-variables'   => json_encode($object),
            'attachment'            => null,
            'o:tag'                 => array('test1'),
            'o:tracking'            => 'yes',
            'o:tracking-opens'      => 'yes',
            'o:tracking-clicks'     => 'yes',
        ]);

		return response()->json(['success'=> true, 'message'=> $sender], 200);

    }
}
