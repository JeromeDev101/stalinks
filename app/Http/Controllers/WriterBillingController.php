<?php

namespace App\Http\Controllers;

use App\Events\WriterPaidEvent;
use App\Http\Requests\WriterPayRequest;
use App\Notifications\WriterBillingReuploadDoc;
use App\Repositories\Contracts\NotificationInterface;
use App\Repositories\Contracts\PaypalInterface;
use App\Services\InvoiceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\User;
use App\Models\BillingWriter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class WriterBillingController extends Controller
{
    public function getList(Request $request){
        $filter = $request->all();
        $list = Article::select('article.*', 'billing_writer.proof_doc_path', 'registration.rate_type', 'registration.writer_price', 'billing_writer.id AS billing_writer_id')
                        ->leftJoin('backlinks', 'article.id_backlink', '=', 'backlinks.id')
                        ->leftJoin('price', 'article.id_writer_price', '=', 'price.id')
                        ->leftJoin('users', 'article.id_writer', '=', 'users.id')
                        ->leftjoin('registration', 'users.email', '=' , 'registration.email')
                        ->leftJoin('billing_writer', 'article.id', '=', 'billing_writer.id_article')
                        ->with('backlink:id,title,status')
                        ->with('country:id,name')
                        ->where('article.status_writer', 'Done')
                        ->where('users.role_id', 4)
                        ->where('users.isOurs', 1)
                        ->with('user.languages')
                        ->with('price')
                        ->orderBy('id', 'desc');

        if (!auth()->user()->isAdmin() && auth()->user()->role_id != 8) {
            $list->where('article.id_writer', auth()->user()->id);
        }

        if( isset($filter['search_backlink'] ) && $filter['search_backlink'] ){
            $list->where('article.id_backlink', $filter['search_backlink']);
        }

        if( isset($filter['writer'] ) && $filter['writer'] ){
            $list->where('article.id_writer', $filter['writer']);
        }

        if( isset($filter['status'] ) && $filter['status'] ){
            if($filter['status'] == 'not paid') {
                $list->whereNull('article.payment_status');
            }
            if($filter['status'] == 'paid') {
                $list->where('article.payment_status', $filter['status']);
            }
        }

        if (isset($filter['date_completed'])) {
            $filter['date_completed'] = json_decode($filter['date_completed']);
        }

        if( isset($filter['date_completed']) && !empty($filter['date_completed']) && $filter['date_completed']->startDate != ''){
            $list->whereDate('date_complete', '>=', Carbon::create($filter['date_completed']->startDate)
                ->format('Y-m-d'));
            $list->whereDate('date_complete', '<=', Carbon::create($filter['date_completed']->endDate)
                ->format('Y-m-d'));
        }

        if (isset($filter['date_created'])) {
            $filter['date_created'] = json_decode($filter['date_created']);
        }

        if( isset($filter['date_created']) && !empty($filter['date_created']) && $filter['date_created']->startDate != ''){
            $list->whereDate('article.created_at', '>=', Carbon::create($filter['date_created']->startDate)
                ->format('Y-m-d'));
            $list->whereDate('article.created_at', '<=', Carbon::create($filter['date_created']->endDate)
                ->format('Y-m-d'));
        }

        return [
            'data' => $list->get(),
        ];
    }

    public function payBilling(WriterPayRequest $request, NotificationInterface $notification, InvoiceService $invoice, PaypalInterface $paypal) {

        if (Gate::denies('update-billing-writer-billing')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $ids = json_decode($request->ids);

        $new_name = null;

//        if ($request->get('id_payment_type') != 1) {
//            $image = $request->file;
//            $new_name = time() . '-billing-writer.' . $image->getClientOriginalExtension();
//            $image->move(public_path('images/billing'), $new_name);
//        }

        $image = $request->file;
        $new_name = time() . '-billing-writer.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/billing'), $new_name);

        DB::beginTransaction();
        $article_ids = [];
        foreach( $ids as $data ){
            $article_id = $data->id;
            $user_id_writer = $data->id_writer;
            $article_ids[] = $article_id;

            $article = Article::FindOrFail($article_id);
            $article->update(['payment_status' => 'Paid']);

            $billing = BillingWriter::create([
                'id_article' => $article_id,
                'id_user' => $user_id_writer,
                'price' => $request->price,
                'id_payment_via' => intVal($request->id_payment_type),
                'date_billing' => date('Y-m-d'),
                'proof_doc_path' => '/images/billing/'.$new_name,
            ]);
        }

//        if ($request->get('id_payment_type') == 1) {
//            $writer = User::find($user_id_writer);
//            $paypalResult = $paypal->createPayout([
//                'email' => $writer->email,
//                'amount' => $request->price
//            ]);
//
//            $payoutResult = $paypal->fetchPayout($paypalResult->result->batch_header->payout_batch_id);
//
//            BillingWriter::where('id_article', $article_id)->update([
//                'fee' => $payoutResult->result->items[0]->payout_item_fee->value
//            ]);
//
//            $invoicePdf = $invoice->generateWriterProof($writer, $article_ids, $request->price, $billing->id, $payoutResult->result->items[0]->payout_item_fee->value);
//
//            $billing->update([
//                'proof_doc_path' =>  $invoicePdf
//            ]);
//        }

        event(new WriterPaidEvent($billing->user, $request->price, $article_ids, $billing->proof_doc_path));

//        $notification->create([
//            'user_id' => $ids[0]->id_writer,
//            'notification' => 'Your account has been credited of '. $request->price .' for the different order '. implode(', ', $article_ids) .' thanks'
//        ]);

        DB::commit();

//        broadcast(new WriterPaidEvent($ids[0]->id_writer));

        return response()->json(['success' => true], 200);
    }

    public function getWriterInfo(Request $request) {
        $writer_id = [];
        $result = [
            'data' => [],
            'success' => false
        ];
        foreach($request->ids as $data){
            if( isset($data['id_writer']) ){
                $writer_id[] = $data['id_writer'];
            }
        }

        $check = array_unique($writer_id);

        if( count($check) > 1){
            return $result;
        }

        if( isset($check[0]) ){
            $writer_info = User::where('id', $check[0])->with(['paymentType', 'registration', 'userPaymentTypes'])->get();

            $result = [
                'data' => $writer_info,
                'success' => true
            ];
        }

        return $result;
    }

    public function downloadPaypalProof($id)
    {
        $file = Storage::get('STAL-WRITER-' . $id . '.pdf');

        return $file;
    }

    public function writerBillingReuploadDoc(Request $request) {
        if (Gate::denies('upload-billing-writer-billing')) {
            return response()->json([
                "message" => 'Unauthorized Access',
                "errors" => [
                    "access" => 'Unauthorized Access',
                ],
            ],422);
        }

        $request->validate([
            'file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        // reupload new document
        $filename = time() . '-billing-writer.' . $request->file->getClientOriginalExtension();
        move_file_to_storage($request->file, 'images/billing', $filename);

        $billing = BillingWriter::find($request->billing_id);

        if ($billing) {
            $billing->update([
                'proof_doc_path' => '/images/billing/' . $filename,
            ]);

            // send email to writer
            $billing->user->notify(new WriterBillingReuploadDoc($billing, $filename));
        }

        return response()->json(['success' => true, 'data' => $billing], 200);
    }
}
