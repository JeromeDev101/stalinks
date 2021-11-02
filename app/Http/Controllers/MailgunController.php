<?php

namespace App\Http\Controllers;

use App\Http\Resources\InboxResource;
use App\Jobs\DeleteEmailAttachments;
use App\MailSignature;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $emailRule = [];

        if (is_array($request->email)) {
            $emailRule['email'] = 'required|array|max:10';
        } else {
            $emailRule['email'] = 'required';
        }

        $rules = [
            'email.*.text' => 'required|email',
            'title'        => 'required',
            'content'      => 'required',
        ];

        $request->validate(array_merge($emailRule, $rules));

        // if ($validator->fails()) {
        //     return response()->json($validator->messages(),422);
        // }

        if ($request->attachment == "undefined") {
            $atth = null;
        } else {
//            $atth = [
//                array('filePath'=>$request->attachment->getRealPath(),'filename'=>$request->attachment->getClientOriginalName()),
//            ];

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

        $email_to = $request->email;

        if (is_array($email_to)) {
            $myArray = array_column($email_to, 'text');
        } else {
            if (strpos($request->email, '|') !== false) {
                $email_to = str_replace("|", ",", $request->email);
            }

            $myArray = explode(',', $email_to);
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

//        // get inline images source
//
//        $inlineImagesSrc = $this->imageSrcExtractor($signature);
//
//        // replace html string images source for mailgun
//
//        $send_signature = str_replace("/storage/uploads/", "cid:", $signature);
//
//        // for html content
//
//        $inlineImagesContentSrc = $this->imageSrcExtractor($request->content);
//
//        $send_content = str_replace("/storage/uploads/", "cid:", $request->content);
//
//        // merge all inline images src
//
//        $inlineImagesAllSrc = array_merge($inlineImagesSrc, $inlineImagesContentSrc);

        $params = [
            'from'                => $work_mail,
            'to'                  => array($str),
            'subject'             => $request->title,
//            'html'                => "<div style='white-space: pre'>" . $request->content . "</div>",
//            'html'                => "<div style='white-space: pre'>" . $send_content . $send_signature . "</div>",
            'html'                => view('send_email', $data)->render(),
            'recipient-variables' => json_encode($object),
            'attachment'          => $atth,
            'o:tag'               => array('test1'),
            'o:tracking'          => 'yes',
            'o:tracking-opens'    => 'yes',
            'o:tracking-clicks'   => 'yes',
//            'inline'              => $inlineImagesAllSrc
        ];

        if (isset($request->cc) && $request->cc != "") {
            $params['bcc'] = $request->cc;
        }

        $sender = $this->mg->messages()->send(config('gun.mail_domain'), $params);

        // saving attachments to database

        $attac_object = null;

        if ($request->attachment != "undefined") {
//            $attach = time().'.'.$request->attachment->getClientOriginalExtension();
//            $request->attachment->move(public_path('/attachment'), $attach);
//
//            $attac_object = [
//                'url'           => url('/attachment/'.$attach),
//                'size'          => \File::size(public_path('/attachment/'), $attach),
//                'type'          => $request->attachment->getClientOriginalExtension(),
//                'filename'      => $attach,
//                'display_name'  => $request->attachment->getClientOriginalName()
//            ];

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

//        $input['body-plain'] = $request->content;
        $input['body-plain'] = "<div style='white-space: pre'>" . $request->content . $signature . "</div>";

        $res = preg_replace("/[<->]/", "", $sender->getId());

        $sendEmail = Reply::create([
            'sender'          => $work_mail,
            'subject'         => $request->title,
            'is_sent'         => 1,
            'is_viewed'       => 1,
            'label_id'        => 0,
            'received'        => $str,
            'body'            => json_encode($input),
            'from_mail'       => $work_mail,
            'attachment'      => $attac_object == null ? '' : json_encode($attac_object),
            'date'            => date('Y-m-d'),
            'message_id'      => $res,
            'references_mail' => '',
            'status_code'     => 0,
            'message_status'  => '',
        ]);

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

    public function recipient_filter(Request $request)
    {
        $cnt = 0;

        //return response()->json(['inbox'=> Auth::user()->role_id]);

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
                    MAX(replies.created_at) AS created_at,
                    MAX(replies.attachment) AS attachment,
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

            switch ($request->param) {
                case 'Inbox':

                    if ($request->email == 'jess@stalinks.com' || $request->email == 'all') {
//                        $inbox = $inbox->where('replies.is_sent', 0)->whereNull('replies.deleted_at');
                        $inbox = $inbox->where('replies.is_sent', 0)
                            ->whereNull('replies.deleted_at')
                            ->with('thread');
                    } else {
//                        $inbox = $inbox->where('replies.received', 'like', '%'.$request->email.'%')
//                            ->where('replies.is_sent', 0)
//                            ->whereNull('replies.deleted_at');

                        $inbox = $inbox->where('replies.is_sent', 0)
                            ->whereNull('replies.deleted_at')
                            ->where('replies.received', 'like', '%' . $request->email . '%')
                            ->with([
                                'thread' => function ($query) use ($request) {
                                    $query->where(function ($sub) use ($request) {
                                        $sub->orWhere('sender', 'like', '%' . $request->email . '%')
                                            ->orWhere('received', 'like', '%' . $request->email . '%');
                                    });
                                }
                            ]);
                    }

                    $inbox = $inbox->groupBy(DB::raw(
                        'CONCAT("Re: ", REPLACE(subject, "Re: ", "")), sender, received')
                    )
                        ->orderBy('id', 'desc')
                        ->paginate();

                    // get emails with "Re: " subjects
                    $subjects = $this->getSubjects($inbox);

                    // add emails with and without 'Re: ' to the thread
                    $inbox = $this->addEmailsWithAndWithoutReInThreads($subjects, $request, $inbox);

                    // sort items for the correct thread and date
                    $inbox = $this->sortEmailThreads($inbox);

                    break;
                case 'Sent':
                    if ($request->email == 'jess@stalinks.com' || $request->email == 'all') {
                        $inbox = $inbox->where('replies.is_sent', 1)->where(function ($sub) {
                            $sub->whereNull('deleted_at')
                                ->orWhere('deleted_at', 1);
                        })
                            ->with('thread');
                    } else {
                        $inbox = $inbox->where('replies.sender', $request->email)
                            ->where('replies.is_sent', 1)
                            ->where(function ($sub) {
                                $sub->whereNull('deleted_at')
                                    ->orWhere('deleted_at', 1);
                            })
                            ->with([
                                'thread' => function ($query) use ($request) {
                                    $query->where(function ($sub) use ($request) {
                                        $sub->orWhere('sender', 'like', '%' . $request->email . '%')
                                            ->orWhere('received', 'like', '%' . $request->email . '%');
                                    });
                                }
                            ]);
                    }

                    $inbox = $inbox->groupBy('subject', 'sender', 'received')
                        ->havingRaw('subject = REPLACE(replies.subject, "Re: ", "")')
                        ->orderBy('id', 'desc')
                        ->paginate();

                    // get emails with "Re: " subjects
                    $subjects = $this->getSubjects($inbox);

                    // add emails with and without 'Re: ' to the thread
                    $inbox = $this->addEmailsWithAndWithoutReInThreads($subjects, $request, $inbox);

                    // sort items for the correct thread and date
                    $inbox = $this->sortEmailThreads($inbox);

                    break;
                case 'Trash':
                    // $inbox = $inbox->withTrashed();
                    // SELECT * FROM `replies` WHERE `deleted_at` != 1 AND `sender` = 'jess@stalinks.com' OR  `deleted_at` != 1 AND `received` = 'jess@stalinks.com'
                    if ($request->email == 'jess@stalinks.com' || $request->email == 'all') {
                        $inbox = $inbox->whereNotNull('replies.deleted_at')->where('replies.deleted_at', '!=', 1);
                    } else {
                        $inbox = $inbox
                            ->whereNotNull('replies.deleted_at')
                            ->where('replies.deleted_at', '!=', 1)
                            ->where(function ($sub) use ($request) {
                                $sub->where('replies.sender', $request->email)
                                    ->orWhere('replies.received', $request->email);
                            });
                    }

                    break;
                case 'Starred':

                    if ($request->email == 'jess@stalinks.com' || $request->email == 'all') {
                        $inbox = $inbox->where('replies.is_starred', 1)
                            ->where(function ($sub) {
                                $sub->whereNull('deleted_at')
                                    ->orWhere('deleted_at', 1);
                            })
                            ->with('thread');;
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
                            })
                            ->with([
                                'thread' => function ($query) use ($request) {
                                    $query->where(function ($sub) use ($request) {
                                        $sub->orWhere('sender', 'like', '%' . $request->email . '%')
                                            ->orWhere('received', 'like', '%' . $request->email . '%');
                                    });
                                }
                            ]);
                    }

                    $inbox = $inbox->groupBy(DB::raw('
                            CONCAT(LEAST(sender, received), "-", GREATEST(sender, received)),
                            CONCAT("Re: ", REPLACE(subject, "Re: ", ""))
                        ')
                    )
                        ->orderBy('id', 'desc')
                        ->paginate();

                    // get emails with "Re: " subjects
                    $subjects = $this->getSubjects($inbox);

                    // add emails with and without 'Re: ' to the thread
                    $inbox = $this->addEmailsWithAndWithoutReInThreads($subjects, $request, $inbox);

                    // sort items for the correct thread and date
                    $inbox = $this->sortEmailThreads($inbox);

                    break;
                default:
                    $inbox = $inbox;
            }
        }

        $inbox = ($request->param != 'Trash') ? $inbox : $inbox->paginate();

        // getting count of unread emails in inbox

        $cnt = Reply::where('is_viewed', 0)
            ->where('is_sent', 0)
            ->whereNull('deleted_at');

        if ($request->email !== 'jess@stalinks.com' && $request->email !== 'all') {
            $cnt = $cnt->where('replies.received', 'like', '%' . $request->email . '%');
        }

        $cnt = $cnt->distinct(DB::raw('CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))'))
            ->count(DB::raw('CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))'));

        return response()->json([
            'count' => $cnt,
            'inbox' => $inbox
        ]);
    }

    private function sortEmailThreads($inbox)
    {
        $inbox->transform(function ($item) {
            $threads = $item->thread->filter(function ($value) use ($item) {
                $received_array = $this->removeSpacesAndExplode($value->received);
                $item_received  = $this->removeSpacesAndExplode($item->received);

                // get from_mail

                $item_from_mail     = $this->getFromMail($item->from_mail);
                $received_from_mail = $this->getFromMail($value->from_mail);

                if ($item->is_sent === 1) {
                    return (
                        ($value->received === $item->received && $value->sender === $item->sender)
                        || ($value->received === $item->received && $received_from_mail === $item->sender)
                        || ($value->sender === $item->received && $value->received === $item->sender)
                        || ($received_from_mail === $item->received && $value->received === $item->sender)
                        || (in_array($value->sender, $item_received) && $value->received === $item->sender)
                        || (in_array($received_from_mail, $item_received) && $value->received === $item->sender)
                        || (in_array($value->received, $item_received) && $value->sender === $item->sender)
                        || (in_array($value->received, $item_received) && $received_from_mail === $item->sender)
                    );
                } else {
                    return (
                        ($value->received === $item->received && $value->sender === $item->sender)
                        || ($value->received === $item->received && $value->sender === $item_from_mail)
                        || ($value->received === $item->sender && $value->sender === $item->received)
                        || ($value->received === $item_from_mail && $value->sender === $item->received)
                        || (in_array($item->sender, $received_array) && $value->sender === $item->received)
                        || (in_array($item_from_mail, $received_array) && $value->sender === $item->received)
                        || (in_array($item->received, $received_array) && $value->sender === $item->sender)
                        || (in_array($item->received, $received_array) && $value->sender === $item_from_mail)
                    );
                }
            })->sortByDesc('id')->values();

            unset($item['thread']);
            $item['thread'] = $threads;

            return $item;
        });

        return $inbox;
    }

    private function addEmailsWithAndWithoutReInThreads($subjects, $request, $inbox)
    {
        $add_inbox = Reply::whereIn('subject', $subjects);

        if ($request->email !== 'jess@stalinks.com' && $request->email !== 'all') {
            $add_inbox = $add_inbox->where(function ($sub) use ($request) {
                $sub->orWhere('sender', 'like', '%' . $request->email . '%')
                    ->orWhere('received', 'like', '%' . $request->email . '%');
            });
        }

        $add_inbox = $add_inbox->get()->groupBy('subject');

        $add_inbox->each(function ($add, $key) use ($inbox) {
            $inbox->transform(function ($inb) use ($key, $add) {
                if ($inb->con_sub == $key || $inb->re_sub == $key) {
                    $new_thread = $add->merge($inb->thread);
                }

                if (isset($new_thread)) {
                    unset($inb['thread']);
                    $inb['thread'] = $new_thread;
                }

                return $inb;
            });
        });

        return $inbox;
    }

    private function getSubjects($inbox)
    {
        $subjects = $inbox->getCollection()->pluck('subject')->unique();

        return $subjects->transform(function ($sub) {
            return strpos($sub, 'Re:') !== false
                ? str_replace('Re: ', "", $sub)
                : 'Re: ' . $sub;
        });
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
        $message_id    = $request->only('Message-Id');
        $message_id    = preg_replace("/[<>]/", "", $message_id['Message-Id']);
        $in_reply_to   = null;
        $references    = null;
        $stripped_html = null;
        $body_html     = null;

        $aw = $this->mg->events()->get('stalinks.com');
        // $r_attachment = '';

        //get all eventsitems============
        foreach ($aw->getItems() as $kwe) {
            //all all message sent/received==========
            foreach ($kwe->getMessage() as $wa) {
                //check if data is array some is not we need to make sure=======
                if (is_array($wa)) {
                    //check if mesage_id property exist before using it as condition=======
                    if (array_key_exists("message-id", $wa)) {
                        if ($wa['message-id'] == $message_id) {
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
        }

        $data = [
            'sender'          => $request->sender,
            'subject'         => $request->subject,
            'body'            => json_encode($request->only('body-plain')),
            'stripped_html'   => $stripped_html,
            'body_html'       => $body_html,
            'attachment'      => json_encode($r_attachment),
            // 'attachment'        => '',
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

        DB::table('replies')->insert($data);

        return response()->json($request->all());
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
        Reply::whereIn('id', $request->ids)->update(['is_viewed' => 1]);

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
            ->select('replies.from_mail as from', 'replies.sender as user_mail', 'replies.received as to', 'replies.status_code as status', 'replies.created_at as date');

        if (isset($request->status) && $request->status != '') {
            $mail_logs = $mail_logs->where('replies.status_code', $request->status);
        }

        if (isset($request->user_email) && $request->user_email != '') {
            $mail_logs = $mail_logs->where('replies.sender', $request->user_email);
        }

        if (isset($request->recipient) && $request->recipient != '') {
            $mail_logs = $mail_logs->where('replies.received', 'like', '%' . $request->recipient . '%');
        }

        $mail_logs = $mail_logs->orderBy('created_at', 'desc')->paginate($request->paginate);

        return $mail_logs;
    }

    public function mail_logs_totals()
    {
        $total      = Reply::where('is_sent', 1)->count();
        $sent_today = Reply::where('is_sent', 1)->where('created_at', 'like', '%' . date('Y-m-d') . '%')->count();
        $sent       = Reply::where('is_sent', 1)->where('status_code', 0)->count();
        $failed     = Reply::where('is_sent', 1)->where('status_code', 552)->count();
        $delivered  = Reply::where('is_sent', 1)->where('status_code', 250)->count();
        $rejected   = Reply::where('is_sent', 1)->where('status_code', 500)->count();
        $opened     = Reply::where('is_sent', 1)->where('status_code', 260)->count();
        $reported   = Reply::where('is_sent', 1)->where('status_code', 570)->count();

        return response()->json([
            'total_mail' => $total,
            'sent'       => $sent,
            'sent_today' => $sent_today,
            'failed'     => $failed,
            'delivered'  => $delivered,
            'rejected'   => $rejected,
            'opened'     => $opened,
            'reported'   => $reported,
        ]);
    }

    public function get_mail_list()
    {
//        $user_email = User::selectRaw('id, role_id, work_mail')
//            ->where('work_mail', '!=', '')
//            ->with('role')
//            ->with('unreadReplies')
//            ->orderBy('work_mail', 'asc')
//            ->groupBy('work_mail')
//            ->get();

        // get unread emails count

        $user_email = User::selectRaw('id, role_id, work_mail, (select count(distinct CONCAT("Re: ", REPLACE(subject, "Re: ", "")), CONCAT(LEAST(sender, received), "-", GREATEST(sender, received))) as aggregate from replies where is_viewed = 0 and is_sent = 0 and deleted_at is null and replies.received like CONCAT("%", users.work_mail ,"%")) as unread_count')
            ->where('work_mail', '!=', '')
            ->with('role')
            ->orderBy('work_mail', 'asc')
            ->groupBy('work_mail')
            ->get();

        return response()->json($user_email);
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
}
