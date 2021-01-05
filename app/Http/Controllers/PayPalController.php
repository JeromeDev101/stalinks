<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payee;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PayPalController extends Controller
{
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function payment(Request $request)
    {

        //var_dump(intval($request->price));
        //return response()->json($request->desription);

        // $data = [];
        // $data['items'] = [
        //     [
        //         'name' => $request->name,
        //         'price' => intval($request->price),
        //         'desc'  => $request->desription,
        //         'qty' => 1
        //     ]
        // ];
  
        // $data['invoice_id'] = 1;
        // $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        // $data['return_url'] = route('payment.success');
        // $data['cancel_url'] = route('payment.cancel');
        // $data['total'] = intval($request->price);
  
        // $provider = new ExpressCheckout;
  
        // $response = $provider->setExpressCheckout($data);
  
        // //$response = $provider->setExpressCheckout($data, true);

        // return response()->json(['status'=> 200, 'data'=> $response['paypal_link']]);
  
        //return redirect($response['paypal_link']);

        $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
                    'Ad9q5Iwgok1_FyGUQqiBBVx3vJca6h1y-NpW-ruBte5h48eRyGeF6027_6wSBdsFtASUJq51KEY6EUPO',     // ClientID
                    'EFsxZxVfjKwWvkNrQetf0oXse14HdTFdAZ5lvi_yhCkVvIeeSwe_dZh_k9vssrivzIfsMS6DLdGlnYJy'      // ClientSecret
                )
        );



        // After Step 2
        
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName('Morley')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setSku("123145423")
            ->setPrice(1);
        

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        $details = new Details();
        $details->setSubtotal(1);

        

        $amount = new Amount();
        $amount->setCurrency("USD")
        ->setTotal(1)
        ->setDetails($details);
        

        $payee = new Payee();
        $payee->setEmail("embreyes21@yahoo.com");

        $transaction = new Transaction();
        $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription("Morley will pay you.")
        ->setPayee($payee)
        ->setInvoiceNumber('32434');

    $baseUrl = 'http://127.0.0.1:8000/';
    $redirectUrls = new RedirectUrls();
    $redirectUrls->setReturnUrl($baseUrl.'/api/payment/success')
    ->setCancelUrl($baseUrl.'/api/cancel');


    $payment = new Payment();
    $payment->setIntent("sale")
    ->setPayer($payer)
    ->setRedirectUrls($redirectUrls)
    ->setTransactions(array($transaction));

    $request = clone $payment;

    try {
    $payment->create($apiContext);
    
    }
    catch (\PayPal\Exception\PayPalConnectionException $ex) {
        // This will print the detailed information on the exception.
        //REALLY HELPFUL FOR DEBUGGING
        return $ex->getData();
    }


    // try {
    //     $payment->create($apiContext);
    // } catch (Exception $ex) {

    //     ResultPrinter::printError("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", null, $request, $ex);
    //     exit(1);
    // }

   

    $approvalUrl = $payment->getApprovalLink();

    // ResultPrinter::printResult("Created Payment Using PayPal. Please visit the URL to Approve.", "Payment", "<a href='$approvalUrl' >$approvalUrl</a>", $request, $payment);

    return redirect($approvalUrl);
    return response()->json( $approvalUrl );

    }
   
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
       return response()->json(['status'=> 200, 'data'=> 'Redirect into other routes']);
    }
  
    /**
     * Responds with a welcome message with instructions
     *
     * @return \Illuminate\Http\Response
     */
    public function success(Request $request)
    {
        return 'success';
        // $response = $provider->getExpressCheckoutDetails($request->token);
  
        // if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
        //    return response()->json(['status'=> 201, 'data'=> 'Paid Successfully!']);
        // }
  
        // return response()->json(['status'=> 503, 'data'=> 'Something went wrong!']);
    }
}