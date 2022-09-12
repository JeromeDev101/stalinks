<?php

namespace App\Http\Controllers;

use App\Http\Resources\InboxResource;
use App\Jobs\DeleteEmailAttachments;
use App\Jobs\StoreEmailAttachments;
use App\MailSignature;
use App\Models\MailAutoReply;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
use App\Jobs\UpdateStatusMailLogs;

class MailgunController extends Controller
{
    private $mg;

    public function __construct()
    {
        $this->mg = Mailgun::create(config('gun.mail_api'));
    }

    public function send(Request $request)
    {
        // check if email is a string or json object

        if (!is_array($request->email)) {
            $request['email'] = json_decode($request->email, true) ?? $request->email;
        }

        if (!is_array($request->cc)) {
            $request['cc'] = json_decode($request->cc, true) ?? $request->cc;
        }

        if (!is_array($request->bcc)) {
            $request['bcc'] = json_decode($request->bcc, true) ?? $request->bcc;
        }

        $emailRule = [];

        if (is_array($request->email)) {
            $emailRule['email'] = 'required|array|max:10';
        } else {
            $emailRule['email'] = 'required';
        }

        $rules = [
            'email.*.text' => 'required|email',
            'bcc.*.text' => 'required|email',
            'cc.*.text' => 'required|email',
            'title'        => 'required',
            'content'      => 'required',
        ];

        $request->validate(array_merge($emailRule, $rules));

        // create attachment object
        if ($request->attachment == "undefined") {
            $atth = null;
        } else {
            if (is_array($request->attachment)) {
                foreach ($request->attachment as $att) {
                    $atth[] = array(
                        'filePath' => $att->getRealPath(),
                        'filename' => $att->getClientOriginalName()
                    );
                }
            } else {
                $atth = null;
            }
        }

        $forward_attachments = null;

        // create forward attachments object

        if ($request->has('forwardAttachment')) {
            if ($request->forwardAttachment == "undefined") {
                $forward_attachments = null;
            } else {
                if ($request->has('is_sent')) {
                    if (is_array($request->forwardAttachment)) {
                        foreach ($request->forwardAttachment as $att) {
                            $decode_attach = json_decode($att);

                            if ($request->is_sent == 0) {
                                $forward_attachments[] = array(
                                    'filePath' => config('app.url') . '/storage/' . $decode_attach->path,
                                    'filename' => $decode_attach->name
                                );
                            } else {
                                $forward_attachments[] = array(
                                    'filePath' => config('app.url') . '/attachment/' . $decode_attach->filename,
                                    'filename' => $decode_attach->display_name
                                );
                            }
                        }
                    } else {
                        $forward_attachments = null;
                    }
                }
            }
        }

        // merge attachments and forward attachments
        $final_attachments = null;

        if ($atth != null && $forward_attachments != null) {
            $final_attachments = array_merge($atth, $forward_attachments);
        } else if ($atth != null && $forward_attachments == null) {
            $final_attachments = $atth;
        } else if ($atth == null && $forward_attachments != null) {
            $final_attachments = $forward_attachments;
        } else {
            $final_attachments = null;
        }

        $email_to = $request->email;

        $myArray = $this->prepareMultipleEmails($email_to, $request);
        $myCc = null;
        $myCcString = null;
        $myBcc = null;
        $myBccString = null;

        if ($request->cc) {
            $myCc = $this->prepareMultipleEmails($request->cc, $request);

            if (is_array($myCc)) {
                $myCcString = implode(",", $myCc);
            }
        }

        if ($request->bcc) {
            $myBcc = $this->prepareMultipleEmails($request->bcc, $request);

            if (is_array($myBcc)) {
                $myBccString = implode(",", $myBcc);
            }
        }

        //add current email another associalte array
        $aw = [];

        foreach ($myArray as $key => $value) {
            $kwe = array(
                $value => [
                    "first" => $request->title,
                    "id"    => $key + 1
                ]
            );
            array_push($aw, $kwe);
        }

        //convert to object
        $object = (object)$aw;

        //arrange all list of emails into string to be ready as one element array_merge
        $list_emails = array();
        foreach ($myArray as $key => $value) {
            $list_emails[$key] = $value;
        }

        $str = implode(", ", $list_emails);

        // work mail and email signature

        $work_mail = $request->has('work_mail') ? $request->work_mail : Auth::user()->work_mail;

        $signature = MailSignature::select('content')->where('work_mail', $work_mail)->first();

        $signature = $signature ? "<div style='width:100%'>" . $signature->content . "</div>" : '';

        // content data

        $send_content = str_replace("/storage/uploads/", config('app.url') . "/storage/uploads/", $request->content);
        $send_signature = str_replace("/storage/uploads/", config('app.url') . "/storage/uploads/", $signature);

        $data = [
            'content' => $send_content,
            'signature' => $send_signature,
        ];

//        dd($this->removeHtmlAndCssTags(view('send_email', $data)->render()));

        $params = [
            'from'                => $work_mail,
            'to'                  => array($str),
            'subject'             => $request->title,
            'cc'                  => $myCcString,
            'bcc'                 => $myBccString,
            'html'                => view('send_email', $data)->render(),
//            'recipient-variables' => json_encode($object),
            'attachment'          => $final_attachments,
            'o:tag'               => array('test1'),
            'o:tracking'          => 'yes',
            'o:tracking-opens'    => 'yes',
            'o:tracking-clicks'   => 'yes',
        ];

//        if (isset($request->cc) && $request->cc != "") {
//            $params['bcc'] = $request->cc;
//        }

        $sender = $this->mg->messages()->send(config('gun.mail_domain'), $params);

        // saving attachments to database
        $attac_object = null;

        if ($request->attachment != "undefined") {
            if (is_array($request->attachment)) {
                foreach ($request->attachment as $att) {
                    $attach = time() . '.' . $att->getClientOriginalExtension();
                    $att->move(public_path('/attachment'), $attach);

                    $attac_object[] = [
                        'url'          => url('/attachment/' . $attach),
                        'size'         => \File::size(public_path('/attachment/'), $attach),
                        'type'         => $att->getClientOriginalExtension(),
                        'filename'     => $attach,
                        'display_name' => $att->getClientOriginalName()
                    ];
                }
            }
        }

        // add forward attachments
        if ($request->forwardAttachment != "undefined") {
            if (is_array($request->forwardAttachment)) {
                foreach ($request->forwardAttachment as $att) {
                    $decode_attach = json_decode($att);

                    if ($request->is_sent == 0) {
                        $attac_object[] = [
                            'url'          => config('app.url') . '/storage/' . $decode_attach->path,
                            'size'         => $decode_attach->size,
                            'type'         => $decode_attach->mime,
                            'filename'     => str_replace('attachments/', '', $decode_attach->path),
                            'display_name' => $decode_attach->name
                        ];
                    } else {
                        $attac_object[] = (array) $decode_attach;
                    }
                }
            }
        }

        $input['body-plain'] = "<div style='white-space: pre'>" . $request->content . $signature . "</div>";
        $body_html['body-html'] = view('send_email', $data)->render();
        $stripped_html['stripped-html'] = view('send_email', $data)->render();

        $res = preg_replace("/[<->]/", "", $sender->getId());

//        $sendEmail = Reply::create([
//            'sender'          => $work_mail,
//            'subject'         => $request->title,
//            'is_sent'         => 1,
//            'is_viewed'       => 1,
//            'label_id'        => 0,
//            'received'        => $str,
//            'body'            => json_encode($input),
//            'from_mail'       => $work_mail,
//            'attachment'      => $attac_object == null ? '' : json_encode($attac_object),
//            'date'            => date('Y-m-d'),
//            'message_id'      => $res,
//            'references_mail' => '',
//            'status_code'     => 0,
//            'message_status'  => '',
//        ]);

        // save emails to db one by one

        foreach ($list_emails as $address) {
            $sendEmail = Reply::create([
                'sender'          => $work_mail,
                'subject'         => $request->title,
                'cc'              => $myCcString,
                'bcc'             => $myBccString,
                'email_to'        => $str,
                'is_sent'         => 1,
                'is_viewed'       => 1,
                'label_id'        => 0,
                'received'        => $address,
                'body'            => json_encode($input),
                'body_html'       => json_encode($body_html),
                'stripped_html'   => json_encode($stripped_html),
                'body_no_html'    => $this->removeHtmlAndCssTags(view('send_email', $data)->render()),
                'from_mail'       => $work_mail,
                'attachment'      => $attac_object == null ? '' : json_encode($attac_object),
                'date'            => date('Y-m-d'),
                'message_id'      => $res,
                'references_mail' => '',
                'status_code'     => 0,
                'message_status'  => '',

                // save from
                'from' => isset($request->from) ? $request->from : null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $sender
        ], 200);
    }

    public function imageSrcExtractor($str)
    {
        //Create a new DOMDocument object.
        $htmlDom = new \DOMDocument();

        //Load the HTML string into our DOMDocument object.
        @$htmlDom->loadHTML($str);

        //Extract all img elements / tags from the HTML.
        $imageTags = $htmlDom->getElementsByTagName('img');

        //Create an array to add extracted images to.
        $extractedImages = array();

        //Loop through the image tags that DOMDocument found.
        foreach ($imageTags as $imageTag) {
            //Get the src attribute of the image.
            $imgSrc = $imageTag->getAttribute('src');

            //Get the alt text of the image.
            $altText = $imageTag->getAttribute('alt');

            //Get the title text of the image, if it exists.
            $titleText = $imageTag->getAttribute('title');

            //Add the image details to our $extractedImages array.
            $extractedImages[] = array(
//                'filePath' => $imgSrc,
'filePath' => str_replace("/storage/", "../storage/app/public/", $imgSrc),
            );
        }

        return $extractedImages;
    }

    public function retrieve_all()
    {
        $aw = $this->mg->events()->get('stalinks.com');

        return response()->json(new Messages(collect($aw->getItems())));
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

        return response()->json(new ShowMessage($message));
    }

    public function recipient_filter (Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100',
            'param' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        if (isset($request->param) && $request->param != '') {
            if ($request->param != 'Trash') {
                $inbox = Reply::selectRaw('
                    MAX(replies.sender) AS sender,
                    MAX(replies.received) AS received,
                    MIN(replies.sender) AS min_sender,
                    MIN(replies.received) AS min_received,
                    MAX(replies.cc) AS cc,
                    MAX(replies.bcc) AS bcc,
                    MIN(replies.cc) as min_cc,
                    MIN(replies.bcc) as min_bcc,
                    MAX(replies.id) AS id,
                    MIN(replies.subject) as subject,
                    CONCAT("Re: ", REPLACE(subject, "Re: ", "")) AS subject2,
                    CONCAT(LEAST(sender, received), "-", GREATEST(sender, received)) as concat_result,
                    MIN(CONCAT("Re: ", subject)) AS con_sub,
                    MIN(REPLACE(subject, "Re: ", "")) AS re_sub,
                    MAX(labels.name) AS label_name,
                    MAX(replies.is_sent) AS is_sent,
                    MAX(labels.color) AS label_color,
                    MAX(replies.label_id) AS label_id,
                    MAX(replies.from_mail) AS from_mail,
                    MIN(replies.from_mail) AS min_from_mail,
                    MAX(replies.created_at) AS created_at,
                    MAX(replies.attachment) AS attachment,
                    MAX(replies.stored_attachments) AS stored_attachments,
                    MAX(replies.is_starred) AS is_starred,
                    MAX(replies.status_code) AS status
                ')
                    ->leftJoin('labels', 'replies.label_id', '=', 'labels.id');
            } else {
                $inbox = Reply::select('replies.*', 'labels.name as label_name', 'labels.color as label_color')
                    ->leftJoin('labels', 'replies.label_id', '=', 'labels.id')
                    ->orderBy('id', 'desc');
            }

            if (isset($request->search_mail) && $request->search_mail != '') {
                $inbox = $inbox->where(function ($query) use ($request) {
                    $query->orWhere('replies.received', 'like', '%' . $request->search_mail . '%')
                        ->orWhere('replies.subject', 'like', '%' . $request->search_mail . '%')
                        ->orWhere('replies.body', 'like', '%' . $request->search_mail . '%')
                        ->orWhere('replies.from_mail', 'like', '%' . $request->search_mail . '%')
                        ->orWhere('replies.sender', 'like', '%' . $request->search_mail . '%');
                });
            }

            if (isset($request->label_id) && $request->label_id != '') {
                $inbox = $inbox->where('replies.label_id', $request->label_id);
            }

            if (isset($request->mail_id) && $request->mail_id != '') {
                $inbox = $inbox->where('replies.id', $request->mail_id);
            }

            if (isset($request->toggle_unread)
                && $request->toggle_unread == 'true'
                && ($request->param == 'Inbox' || $request->param == 'Starred')) {

                $inbox = $inbox->where('is_viewed', 0);
            }

            switch ($request->param) {
                case 'Inbox':

                    if ($request->email == 'all') {
                        $inbox = $inbox->where('replies.is_sent', 0)
                            ->whereNull('replies.deleted_at');
                    } else {
                        $inbox = $inbox->where('replies.is_sent', 0)
                            ->whereNull('replies.deleted_at')
                            ->where('replies.received', 'like', '%' . $request->email . '%');
                    }

                    $inbox = $inbox->groupBy(DB::raw(
                        'CONCAT("Re: ", REPLACE(subject, "Re: ", "")), sender, received')
                    )
                    ->orderBy('id', 'desc')
                    ->paginate(10);

                    $inbox->getCollection()->transform(function ($item) use ($request) {
                        $item['thread'] = $this->getThreads($item, $request);

                        return $item;
                    });

                    break;

                case 'Sent':
                    if ($request->email == 'all') {
                        $inbox = $inbox->where('replies.is_sent', 1)->where(function ($sub) {
                            $sub->whereNull('deleted_at')
                                ->orWhere('deleted_at', 1);
                        });
                    } else {
                        $inbox = $inbox->where('replies.sender', $request->email)
                            ->where('replies.is_sent', 1)
                            ->where(function ($sub) {
                                $sub->whereNull('deleted_at')
                                    ->orWhere('deleted_at', 1);
                            });
                    }

                    $inbox = $inbox->groupBy('subject', 'sender', 'received')
                        ->havingRaw('subject = REPLACE(replies.subject, "Re: ", "")')
                        ->orderBy('id', 'desc')
                        ->paginate(10);

                    $inbox->getCollection()->transform(function ($item) use ($request) {
                        $item['thread'] = $this->getThreads($item, $request);

                        return $item;
                    });

                    break;

                case 'Trash':
                    if ($request->email == 'all') {
                        $inbox = $inbox->whereNotNull('replies.deleted_at')->where('replies.deleted_at', '!=', 1);
                    } else {
                        $inbox = $inbox
                            ->whereNotNull('replies.deleted_at')
                            ->where('replies.deleted_at', '!=', 1)
                            ->where(function ($sub) use ($request) {
                                $sub->orWhere('replies.sender', 'like', '%' . $request->email . '%')
                                    ->orWhere('replies.received', 'like', '%' . $request->email . '%');
                            });
                    }

                    break;

                case 'Starred':

                    if ($request->email == 'all') {
                        $inbox = $inbox->where('replies.is_starred', 1)
                            ->where(function ($sub) {
                                $sub->whereNull('deleted_at')
                                    ->orWhere('deleted_at', 1);
                            });
                    } else {
                        $inbox = $inbox
                            ->where('replies.is_starred', 1)
                            ->where(function ($sub) use ($request) {
                                $sub->where('replies.sender', $request->email)
                                    ->orWhere('replies.received', $request->email);
                            })
                            ->where(function ($sub) {
                                $sub->whereNull('deleted_at')
                                    ->orWhere('deleted_at', 1);
                            });
                    }

                    $inbox = $inbox->groupBy(DB::raw('
                            CONCAT(LEAST(sender, received), "-", GREATEST(sender, received)),
                            CONCAT("Re: ", REPLACE(subject, "Re: ", ""))
                        ')
                    )
                    ->orderBy('id', 'desc')
                    ->paginate(10);

                    $inbox->getCollection()->transform(function ($item) use ($request) {
                        $item['thread'] = $this->getThreads($item, $request);

                        return $item;
                    });

                    break;

                default:

                    $inbox = $inbox;
            }
        }

        $inbox = ($request->param != 'Trash') ? $inbox : $inbox->paginate(10);

        // getting count of unread emails in inbox

        $cnt = Reply::where('is_viewed', 0)
            ->where('is_sent', 0)
            ->whereNull('deleted_at');

        if ($request->email !== 'all') {
            $cnt = $cnt->where('replies.received', 'like', '%' . $request->email . '%');
        }

        $cnt = $cnt->distinct(DB::raw('CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))'))
            ->count(DB::raw('CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))'));

        return response()->json([
            'count' => $cnt,
            'inbox' => $inbox
        ]);
    }

    private function removeSpacesAndExplode($str)
    {
        $string = preg_replace('/\s+/', '', $str);

        return explode(',', $string);
    }

    private function getFromMail($from_mail)
    {
        if (preg_match('/<(.*?)>/', $from_mail, $match) == 1) {
            return $match[1];
        } else {
            return $from_mail;
        }
    }

    public function status_mail()
    {
        // call job to update mail log status
        UpdateStatusMailLogs::dispatchNow();
    }

    public function post_reply(Request $request)
    {
        // get all attachments from request
        $files = $request->allFiles();

        // call job to store email attachments
        $stored_attachments = StoreEmailAttachments::dispatchNow($files);

        $stored_attachments_data = count($stored_attachments) > 0 ? json_encode($stored_attachments) : null;

        $message_id = $request->only('Message-Id');
        $message_id = preg_replace("/[<>]/", "", $message_id['Message-Id']);
        $in_reply_to = null;
        $references = null;
        $stripped_html = null;
        $body_html = null;
        $body_no_html = null;
        $to = null;
        $bcc = null;
        $cc = null;

        $r_attachment = [];

        // log request
//        Log::info('post_reply: ', ['request' => $request->all()]);

        $input = $request->all();

        DB::table('test_replies')->insert(['alldata' => json_encode($input)]);

        if (isset($input['In-Reply-To']) && $input['In-Reply-To']) {
            $in_reply_to = preg_replace("/[<>]/", "", $input['In-Reply-To']);
        }

        if (isset($input['References']) && $input['References']) {
            $references = preg_replace("/[<>]/", "", $input['References']);
        }

        if (isset($input['stripped-html']) && $input['stripped-html']) {
            $stripped_html = json_encode($request->only('stripped-html'));
        }

        if (isset($input['body-html']) && $input['body-html']) {
            $body_html = json_encode($request->only('body-html'));
            $body_no_html = $this->removeHtmlAndCssTags($input['body-html']);
        }

        if (isset($input['To']) && $input['To']) {
            $to = $this->getStringBetween($input['To'], ['<', '>']);
        }

        if (isset($request->Bcc) && $request->Bcc) {
            $bcc = $this->getStringBetween($request->Bcc, ['<', '>']);
        }

        if (isset($request->Cc) && $request->Cc) {
            $cc = $this->getStringBetween($request->Cc, ['<', '>']);
        }

        $data = [
            'sender'          => $request->sender,
            'subject'         => $request->subject,
            'cc'              => $cc,
            'bcc'             => $bcc,
            'email_to'        => $to,
            'body'            => json_encode($request->only('body-plain')),
            'stripped_html'   => $stripped_html,
            'body_html'       => $body_html,
            'body_no_html'    => $body_no_html,
            'attachment'      => json_encode($r_attachment),
            'stored_attachments'      => $stored_attachments_data,
            'from_mail'       => $request->from,
            'date'            => '',
            'message_id'      => $message_id,
            'in_reply_to'     => $in_reply_to,
            'references'      => $references,
            'received'        => $request->recipient,
            'references_mail' => '',
            'label_id'        => 0,
            'is_starred'      => 0,
            'deleted_at'      => null,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
            'status_code'     => 200,
            'message_status'  => 'message received'
        ];

//        DB::table('replies')->insert($data);

        Reply::firstOrCreate([
            'sender'          => $request->sender,
            'subject'         => $request->subject,
            'cc'              => $cc,
            'bcc'             => $bcc,
            'email_to'        => $to,
            'body'            => json_encode($request->only('body-plain')),
            'stripped_html'   => $stripped_html,
            'body_html'       => $body_html,
            'attachment'      => json_encode($r_attachment),
            'stored_attachments'      => $stored_attachments_data,
            'from_mail'       => $request->from,
            'date'            => '',
            'message_id'      => $message_id,
            'in_reply_to'     => $in_reply_to,
            'references'      => $references,
            'received'        => $request->recipient,
            'references_mail' => '',
        ], $data);

        // send auto reply if on

        $this->sendAutoReply($request);

        return response()->json($request->all());
    }

    public function sendAutoReply ($request) {
        $user = User::where('work_mail', $request->recipient)->first();

        if ($user->work_mail) {
            $auto_reply = MailAutoReply::where('user_id', $user->id)->where('active', 1)->first();

            if ($auto_reply) {
                $sender = $this->getSender($request->from);

                $signature = $this->getEmailSignatureContent($user->work_mail);

                $content_data = $this->getEmailContentData($auto_reply->body, $signature);

                // send auto reply
                $params = [
                    'from'                => $user->work_mail,
                    'to'                  => array($sender),
                    'subject'             => $auto_reply->subject,
                    'html'                => view('send_email', $content_data)->render(),
                    'o:tag'               => array('test1'),
                    'o:tracking'          => 'yes',
                    'o:tracking-opens'    => 'yes',
                    'o:tracking-clicks'   => 'yes',
                ];

                $email = $this->mg->messages()->send(config('gun.mail_domain'), $params);

                // save to database
                $input['body-plain'] = "<div style='white-space: pre'>" . $auto_reply->body . $signature . "</div>";

                $message_id = preg_replace("/[<->]/", "", $email->getId());

                Reply::create([
                    'sender'          => $user->work_mail,
                    'subject'         => $auto_reply->subject,
                    'is_sent'         => 1,
                    'is_viewed'       => 1,
                    'label_id'        => 0,
                    'received'        => $sender,
                    'body'            => json_encode($input),
                    'from_mail'       => $user->work_mail,
                    'attachment'      => '',
                    'date'            => date('Y-m-d'),
                    'message_id'      => $message_id,
                    'references_mail' => '',
                    'status_code'     => 0,
                    'message_status'  => '',
                ]);
            }
        }
    }

    public function getSender ($sender) {
        if (preg_match('/<(.*?)>/', $sender, $match) == 1) {
            return $match[1];
        } else {
            return null;
        }
    }

    public function getEmailSignatureContent ($work_mail) {
        $signature = MailSignature::select('content')->where('work_mail', $work_mail)->first();

        return $signature ? "<div style='width:100%'>" . $signature->content . "</div>" : '';
    }

    public function getEmailContentData ($content, $signature) {
        $send_content = str_replace("/storage/uploads/", config('app.url') . "/storage/uploads/", $content);
        $send_signature = str_replace("/storage/uploads/", config('app.url') . "/storage/uploads/", $signature);

        return [
            'content' => $send_content,
            'signature' => $send_signature,
        ];
    }

    public function starred(Request $request)
    {
        // dd($request->all());
        if (is_array($request->id)) {
            foreach ($request->id as $data) {
                $record = json_decode($data);
                $reply  = Reply::findOrFail($record->id);
                $reply->update(['is_starred' => 1]);
            }
        } else {
            $reply = Reply::findOrFail($request->id);
            $reply->update([
                'is_starred' => $request->is_starred == 0 ? 1 : 0,
            ]);
        }

        return response()->json(['succsss' => true], 200);
    }

    public function starredThread(Request $request)
    {
        Reply::whereIn('id', $request->id)->update(['is_starred' => $request->is_starred]);

        return response()->json(['success' => true], 200);
    }

    public function setViewMessage(Request $request)
    {
        $inbox = Reply::findOrFail($request->id);
        $inbox->update(['is_viewed' => 1]);

        return response()->json(['success' => true], 200);
    }

    public function setViewMessageThread(Request $request)
    {
        $mark = $request->mode === 'read' ? 1 : 0;

        Reply::whereIn('id', $request->ids)->update(['is_viewed' => $mark]);

        return response()->json(['success' => true], 200);
    }

    public function labeling(Request $request)
    {
        if (is_array($request->ids)) {
            foreach ($request->ids as $ids) {
                $inbox = Reply::findOrFail($ids);
                $inbox->update($request->only('label_id'));
            }
        }

        return response()->json(['success' => true], 200);
    }

    public function labelingThread(Request $request)
    {
        Reply::whereIn('id', $request->ids)->update(['label_id' => $request->label_id]);

        return response()->json(['success' => true], 200);
    }

    public function deleteMessage(Request $request)
    {
        if (is_array($request->id)) {
            foreach ($request->id as $id) {
                $inbox = Reply::findOrFail($id);
                $inbox->update(['deleted_at' => date('Y-m-d')]);
            }
        } else {
            $inbox = Reply::findOrFail($request->id);
            $inbox->update(['deleted_at' => date('Y-m-d')]);
        }

        return response()->json(['success' => true], 200);
    }

    public function deleteMessageThread(Request $request)
    {
        Reply::whereIn('id', $request->ids)->update(['deleted_at' => date('Y-m-d')]);

        return response()->json(['success' => true], 200);
    }

    public function retrieveMessage(Request $request)
    {
        $reply = Reply::whereIn('id', $request->ids)
            ->update([
                'deleted_at' => null
            ]);

        return response()->json($reply);
    }

    public function get_reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages());
        }

        $replies = DB::table('replies')->where('sender', Auth::user()->work_mail)->where('received', $request->email)->orderBy('created_at', 'DESC')->get();

        return response()->json($replies);
    }

    public function show_attachment(Request $request)
    {
        return $this->mg->attachment()->show($request->url);
    }

    public function check_domain(Request $request)
    {
        try {
            $client   = new \GuzzleHttp\Client();
            $request  = $client->get($request->domain);
            $response = $request;

            return response()->json([
                'status'  => $response->getStatusCode(),
                'message' => $response->getReasonPhrase()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 401,
                'message' => $e->getHandlerContext()['error']
            ]);
        }
    }

    public function mail_logs(Request $request)
    {
        $mail_logs = DB::table('replies')
            ->where('replies.is_sent', 1)
            ->select(
                'replies.id',
                'replies.from_mail as from_mail',
                'replies.sender as user_mail',
                'replies.received as to',
                'replies.status_code as status',
                'replies.created_at as date',
                'replies.body',
                'replies.body_html',
                'replies.stripped_html',
                'replies.subject',
                'replies.attachment',
                'replies.from'
            );

        if (isset($request->status) && $request->status != '') {
            $mail_logs = $mail_logs->where('replies.status_code', $request->status);
        }

        if (isset($request->subject) && $request->subject != '') {
            $mail_logs = $mail_logs->where('replies.subject', 'like', '%' . $request->subject . '%');
        }

        if (isset($request->user_email) && $request->user_email != '') {
            $mail_logs = $mail_logs->where('replies.sender', $request->user_email);
        }

        if (isset($request->from_page) && $request->from_page != '') {
            if ($request->from_page === 'none') {
                $mail_logs = $mail_logs->whereNull('replies.from');
            } else {
                $mail_logs = $mail_logs->where('replies.from', 'like', '%' . $request->from_page . '%');
            }
        }

        if (isset($request->date) && $request->date != '') {
            $date = \GuzzleHttp\json_decode($request->date, true);
            if (!empty($date) && $date['startDate'] != null) {
                $mail_logs = $mail_logs->whereDate('created_at', '>=', Carbon::create($date['startDate'])->format('Y-m-d'))
                    ->whereDate('created_at', '<=', Carbon::create($date['endDate'])->format('Y-m-d'));
            }
        }

        if (isset($request->recipient) && $request->recipient != '') {
            $mail_logs = $mail_logs->where('replies.received', 'like', '%' . $request->recipient . '%');
        }

        $mail_logs = $mail_logs->orderBy('created_at', 'desc')->paginate($request->paginate);

        return $mail_logs;
    }

    public function mail_logs_totals(Request $request)
    {
        $totals = DB::table('replies')
            ->selectRaw('count(*) as total_mail')
            ->selectRaw("count(case when created_at LIKE '%" . date('Y-m-d') . "%' then 1 end) as sent_today")
            ->selectRaw("count(case when status_code = 0 then 1 end) as sent")
            ->selectRaw("count(case when status_code = 552 then 1 end) as failed")
            ->selectRaw("count(case when status_code = 250 then 1 end) as delivered")
            ->selectRaw("count(case when status_code = 500 then 1 end) as rejected")
            ->selectRaw("count(case when status_code = 260 then 1 end) as opened")
            ->selectRaw("count(case when status_code = 570 then 1 end) as reported")
            ->selectRaw("count(case when replies.from = 'inbox' then 1 end) as inbox")
            ->selectRaw("count(case when replies.from = 'drafts' then 1 end) as drafts")
            ->selectRaw("count(case when replies.from = 'prospect' then 1 end) as prospect")
            ->selectRaw("count(case when replies.from = 'registration' then 1 end) as registration");

        if (isset($request->status) && $request->status != '') {
            $totals = $totals->where('replies.status_code', $request->status);
        }

        if (isset($request->subject) && $request->subject != '') {
            $totals = $totals->where('replies.subject', 'like', '%' . $request->subject . '%');
        }

        if (isset($request->user_email) && $request->user_email != '') {
            $totals = $totals->where('replies.sender', $request->user_email);
        }

        if (isset($request->from_page) && $request->from_page != '') {
            if ($request->from_page === 'none') {
                $totals = $totals->whereNull('replies.from');
            } else {
                $totals = $totals->where('replies.from', 'like', '%' . $request->from_page . '%');
            }
        }

        if (isset($request->date) && $request->date != '') {
            $date = \GuzzleHttp\json_decode($request->date, true);
            if (!empty($date) && $date['startDate'] != null) {
                $totals = $totals->whereDate('created_at', '>=', Carbon::create($date['startDate'])->format('Y-m-d'))
                    ->whereDate('created_at', '<=', Carbon::create($date['endDate'])->format('Y-m-d'));
            }
        }

        if (isset($request->recipient) && $request->recipient != '') {
            $totals = $totals->where('replies.received', 'like', '%' . $request->recipient . '%');
        }

        $totals = $totals->where('replies.is_sent', 1)->first();

        return response()->json($totals);
    }

    public function get_mail_list()
    {
        // get unread emails count

//        $user_email = User::selectRaw('id, role_id, work_mail, (select count(distinct CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))) as aggregate from replies where is_viewed = 0 and is_sent = 0 and deleted_at is null and replies.received like CONCAT("%", users.work_mail ,"%")) as unread_count')
//            ->where('work_mail', '!=', '')
//            ->with('role')
//            ->orderBy('work_mail', 'asc')
//            ->groupBy('work_mail')
//            ->get();

        $user_email = User::selectRaw('id, role_id, work_mail')
            ->where('work_mail', '!=', '')
            ->with('role')
            ->orderBy('work_mail', 'asc')
            ->groupBy('work_mail')
            ->get();

        $unread_count = User::selectRaw("users.id,role_id,work_mail,
            COUNT(
                DISTINCT CONCAT(
                    'Re: ',
                REPLACE
                    (SUBJECT, 'Re: ', '')
                ),
                CONCAT(
                    LEAST(sender, received),
                    '-',
                    GREATEST(sender, received)
                )
            ) AS unread_count")
            ->join('replies', 'replies.received', 'LIKE', DB::raw("CONCAT('%', users.work_mail, '%')"))
            ->where('work_mail', '!=', '')
            ->where('replies.is_viewed', 0)
            ->where('replies.is_sent', 0)
            ->whereNull('replies.deleted_at')
            ->orderBy('work_mail', 'asc')
            ->groupBy('work_mail')
            ->get();


        $final = [];

        foreach ($user_email->toArray() as $email) {
            foreach ($unread_count as $unread) {
                if ($email['id'] === $unread['id']) {
                    $email['unread_count'] = $unread['unread_count'];
                    break;
                } else {
                    $email['unread_count'] = 0;
                }
            }

            $final[] = $email;
        }

        return response()->json($final);
    }

    public function send_validation(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'title' => 'required',
        ]);

        $email_to = $request->email;

        if (strpos($request->email, '|') !== false) {
            $email_to = str_replace("|", ",", $request->email);
        }

        $myArray = explode(',', $email_to);

        //add current email another associalte array
        $aw = [];

        foreach ($myArray as $key => $value) {
            $kwe = array(
                $value => [
                    "first" => $request->title,
                    "id"    => $key + 1
                ]
            );
            array_push($aw, $kwe);
        }

        //convert to object
        $object = (object)$aw;

        $list_emails = array();
        foreach ($myArray as $key => $value) {
            $list_emails[$key] = $value;
        }

        $str = implode(", ", $list_emails);

        $registration = Registration::where('email', $request->email)->first();

        if ($registration->id) {
            $data = [
                'name'              => $registration->name,
                'verification_code' => $registration->verification_code
            ];
        }

        $sender = $this->mg->messages()->send('stalinks.com', [
            'from'                => 'stalinks_validation@stalinks.com',
            'to'                  => array($str),
            // 'bcc'                   => 'lhabzter21@gmail.com',
            'subject'             => $request->title,
            'text'                => 'Email Validation',
            'html'                => view('email', $data)->render(),
            'recipient-variables' => json_encode($object),
            'attachment'          => null,
            'o:tag'               => array('test1'),
            'o:tracking'          => 'yes',
            'o:tracking-opens'    => 'yes',
            'o:tracking-clicks'   => 'yes',
        ]);

        return response()->json([
            'success' => true,
            'message' => $sender
        ], 200);
    }


    /**
     * Deletes email attachments with timestamp < 30 days/1 month
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteOldAttachments () {

        // call job to delete email attachments older than 30 days
        DeleteEmailAttachments::dispatchNow();

        return response('Success', 200);
    }

    public function sendValidationEmail(Request $request)
    {
        // get recipients

        $recipients = $this->sortRecipientData($request->accounts, 'email');

        // get recipient ids

        $recipient_ids = $this->sortRecipientData($request->accounts, 'id');

        // get user, work mail and signature

        $data = $this->generateWorkMailAndSignature();

        $params = [
            'from'                => $data['work_mail'],
            'to'                  => $recipients,
            'subject'             => 'Stalinks Validation',
            'html'                => view('validation_email', $data['content_data'])->render(),
            'o:tag'               => array('test1'),
            'o:tracking'          => 'yes',
            'o:tracking-opens'    => 'yes',
            'o:tracking-clicks'   => 'yes',
        ];

        $sender = $this->mg->messages()->send(config('gun.mail_domain'), $params);

        // update registration table

        $this->updateEmailViaForUsersWithValidationEmail($recipient_ids);

        return response()->json([
            'success' => true,
            'message' => $sender
        ], 200);
    }

    protected function updateEmailViaForUsersWithValidationEmail($recipient_ids) {
        Registration::whereIn('id', $recipient_ids)->update([
            'email_via' => 'validation_email',
            'validation_reminded_at' => Carbon::now()
        ]);
    }

    public function sendDepositEmail (Request $request) {
        // get recipients

        $recipients = $this->sortRecipientData($request->accounts, 'email');

        // get recipient ids

        $recipient_ids = $this->sortRecipientData($request->accounts, 'id');

        // get user, work mail and signature

        $data = $this->generateWorkMailAndSignature();

        $params = [
            'from'                => $data['work_mail'],
            'to'                  => $recipients,
            'subject'             => 'Buyer Credit',
            'html'                => view('buyer.deposit_email', $data['content_data'])->render(),
            'o:tag'               => array('test1'),
            'o:tracking'          => 'yes',
            'o:tracking-opens'    => 'yes',
            'o:tracking-clicks'   => 'yes',
        ];

        $sender = $this->mg->messages()->send(config('gun.mail_domain'), $params);

        // update registration table

        $this->updateEmailViaForUsersWithDepositEmail($recipient_ids);

        return response()->json([
            'success' => true,
            'message' => $sender
        ], 200);
    }

    protected function updateEmailViaForUsersWithDepositEmail($recipient_ids) {

        foreach ($recipient_ids as &$id) {
            Registration::where('id', $id)->update([
                'email_via_others' => 'deposit_email',
                'deposit_reminded_at' => Carbon::now(),
                'survey_code' => md5(uniqid(rand(), true))
            ]);
        }

    }

    protected function sortRecipientData ($accounts, $property) {
        return array_map(function($o) use ($property) { return $o[$property];}, $accounts);
    }

    protected function generateWorkMailAndSignature() {
        $user = User::with('role')->find(Auth::id());

        $work_mail = $user->work_mail;

        $signature = $this->getEmailSignatureContent($work_mail);
        $final_signature = str_replace("/storage/uploads/", config('app.url') . "/storage/uploads/", $signature);

        $content_data = [
            'user' => $user,
            'signature' => $final_signature,
        ];

        return [
            'work_mail' => $work_mail,
            'content_data' => $content_data
        ];
    }

    public function getThreads ($item, $request) {

        // get received
        $item_received = $this->removeSpacesAndExplode($item->received);
        $item_min_received = $this->removeSpacesAndExplode($item->min_received);

        // get from mail
        $item_from_mail = $this->getFromMail($item->from_mail);
        $min_item_from_mail = $this->getFromMail($item->min_from_mail);

        $replies = Reply::where(function ($sub) use ($item) {
                $sub->orWhere('subject', $item->subject)
                    ->orWhere('subject', $this->getAlternateSubject($item->subject));
            })
            ->where(function ($sub) use (
                $item,
                $request,
                $item_from_mail,
                $min_item_from_mail,
                $item_received, $item_min_received
            ) {
                $sub->where(function ($inner) use ($item, $request) {
                    $inner->orWhere('received', $item->received)
                        ->where('sender', $item->sender);
                })

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('received', $item->sender)
                        ->where('sender', $item->received);
                })

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('received', $item->min_received)
                        ->where('sender', $item->min_sender);
                })

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('received', $item->min_sender)
                        ->where('sender', $item->min_received);
                })

                // item from mail

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('received', $item->sender)
                        ->where('sender', $item_from_mail);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('received', $item_from_mail)
                        ->where('sender', $item->sender);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('received', $item->received)
                        ->where('sender', $item_from_mail);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('received', $item_from_mail)
                        ->where('sender', $item->received);
                })

                // min item from mail

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('received', $item->sender)
                        ->where('sender', $min_item_from_mail);
                })

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('received', $min_item_from_mail)
                        ->where('sender', $item->sender);
                })

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('received', $item->received)
                        ->where('sender', $min_item_from_mail);
                })

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('received', $min_item_from_mail)
                        ->where('sender', $item->received);
                })

                // item received

                ->orWhere(function ($inner) use ($item, $request, $item_received) {
                    $inner->orWhereIn('received', $item_received)
                        ->where('sender', $item->received);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_received) {
                    $inner->orWhere('received', $item->received)
                        ->whereIn('sender', $item_received);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_received) {
                    $inner->orWhereIn('received', $item_received)
                        ->where('sender', $item->sender);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_received) {
                    $inner->orWhere('received', $item->sender)
                        ->whereIn('sender', $item_received);
                })

                // min item received

                ->orWhere(function ($inner) use ($item, $request, $item_min_received) {
                    $inner->orWhereIn('received', $item_min_received)
                        ->where('sender', $item->received);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_min_received) {
                    $inner->orWhere('received', $item->received)
                        ->whereIn('sender', $item_min_received);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_min_received) {
                    $inner->orWhereIn('received', $item_min_received)
                        ->where('sender', $item->sender);
                })

                ->orWhere(function ($inner) use ($item, $request, $item_min_received) {
                    $inner->orWhere('received', $item->sender)
                        ->whereIn('sender', $item_min_received);
                })

                // find in set

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('sender', $item->sender)
                        ->whereRaw("FIND_IN_SET('" . "$item->received" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('sender', $item->received)
                        ->whereRaw("FIND_IN_SET('" . "$item->sender" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('sender', $item->min_sender)
                        ->whereRaw("FIND_IN_SET('" . "$item->min_received" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request) {
                    $inner->orWhere('sender', $item->min_received)
                        ->whereRaw("FIND_IN_SET('" . "$item->min_sender" . "',REPLACE(received, ' ', ''))");
                })

                // find in set - item from mail

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('sender', $item->sender)
                        ->whereRaw("FIND_IN_SET('" . "$item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('sender', $item->received)
                        ->whereRaw("FIND_IN_SET('" . "$item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('sender', $item->min_sender)
                        ->whereRaw("FIND_IN_SET('" . "$item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request, $item_from_mail) {
                    $inner->orWhere('sender', $item->min_received)
                        ->whereRaw("FIND_IN_SET('" . "$item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                // find in set - min item from mail

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('sender', $item->sender)
                        ->whereRaw("FIND_IN_SET('" . "$min_item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('sender', $item->received)
                        ->whereRaw("FIND_IN_SET('" . "$min_item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('sender', $item->min_sender)
                        ->whereRaw("FIND_IN_SET('" . "$min_item_from_mail" . "',REPLACE(received, ' ', ''))");
                })

                ->orWhere(function ($inner) use ($item, $request, $min_item_from_mail) {
                    $inner->orWhere('sender', $item->min_received)
                        ->whereRaw("FIND_IN_SET('" . "$min_item_from_mail" . "',REPLACE(received, ' ', ''))");
                });

            })
            ->orderBy('id', 'ASC')
            ->get();

            return $replies->unique(function ($item) {
                return $item['subject'].$item['cc'].$item['bcc'].$item['email_to'].$item['body_no_html'].$item['message_id'];
            })->sortByDesc('id')
            ->values()
            ->toArray();

//            if ($request->param === 'Sent') {
//                $replies = $replies->sortByDesc('is_sent')->values();
//            } else if ($request->param === 'Inbox') {
//                $replies = $replies->sortBy('is_sent')->values();
//            }
//
//            $unique = $replies->unique(function ($item) {
//                return $item['subject'].$item['cc'].$item['bcc'].$item['email_to'].$item['body_no_html'].$item['message_id'];
//            });
//
//            $replies = $unique->sortByDesc('id')
//            ->values()
//            ->toArray();
//
//            return $replies;
    }

    public function getAlternateSubject ($subject) {
        return strpos($subject, 'Re:') !== false
            ? str_replace('Re: ', "", $subject)
            : 'Re: ' . $subject;
    }

    public function getStringBetween ($string, Array $params) {
        $string_array = explode(',', $string);
        $new_array = [];

        foreach ($string_array as $temp) {
            if (Str::contains($temp, $params)) {
                $after = Str::after($temp, $params[0]);
                $new_array[] = Str::before($after, $params[1]);
            } else {
                $new_array[] = $temp;
            }
        }

        return implode(',', $new_array);
    }

    public function prepareMultipleEmails ($emails, $request) {
        $emailArray = [];

        if (is_array($emails)) {
            $emailArray = array_column($emails, 'text');
        } else {
            if (strpos($request->email, '|') !== false) {
                $temp = str_replace("|", ",", $request->email);
            }

            $emailArray = explode(',', $temp);
        }

        return $emailArray;
    }

    public function removeHtmlAndCssTags ($html) {
        $text = strip_tags($html,"<style>");

//        $substring = substr($text,strpos($text,"<style"),strpos($text,"</style>"));
//        $text = str_replace($substring,"",$text);

        $text = preg_replace('/<style[\s\S]+?<\/style>/', '', $text);

        $text = str_replace(array("\t","\r","\n"),"",$text);
        return trim($text);
    }
}
