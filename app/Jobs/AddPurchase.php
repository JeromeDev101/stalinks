<?php

namespace App\Jobs;

use App\Http\Requests\PurchaseRequest;
use App\Services\PurchaseService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Validator;

class AddPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request, $model, $mod;

    /**
     * Create a new job instance.
     *
     * @param Request $request
     * @param Model $model
     * @param $mod
     */
    public function __construct(Request $request, Model $model, $mod = null)
    {
        $this->request = $request;
        $this->model = $model;
        $this->mod = $mod;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // generate request obj
        $purchaseRequest = $this->preparePurchaseRequest($this->request, $this->mod);

        // validate request
        Validator::make(
            $purchaseRequest->all(),
            (new PurchaseRequest())->rules(),
            (new PurchaseRequest())->messages()
        )->validate();

        // add purchase
        (new PurchaseService())->store($purchaseRequest, 'tool', $this->model);
    }

    protected function preparePurchaseRequest ($request, $mod) {
        $purchaseRequest = new Request();
        $purchaseRequest->setMethod('POST');
        $purchaseRequest->request->add([
            'purchase_from' => $request->name,
            'purchase_amount' => $request->purchase_amount,
            'purchase_type_id' => $request->purchase_type_id,
            'purchase_purchased_at' => $mod === 'renew'
                ? Carbon::today()->format('Y-m-d')
                : $request->registered_at,
            'purchase_payment_type_id' => $request->purchase_payment_type_id,
            'purchase_notes' => $request->purchase_notes,
            'purchase_receipt' => $request->purchase_receipt,
            'purchase_invoice' => $request->purchase_invoice,
        ]);

        return $purchaseRequest;
    }
}
