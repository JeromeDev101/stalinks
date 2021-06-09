<?php

namespace App\Services;

use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class InvoiceService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Party([
            'name'    => 'Stalinks',
            'address' => 'Unit 912, 9/F, Two Harbourfront, 22 Tak Fung Street, Hunghom, Kowloon, Hong Kong'
        ]);
    }

    public function generateCreditInvoice($payload)
    {
        $user = auth()->user();
        $customer = new Party([
            'custom_fields' => [
                'company' => optional($user->registration)->company_name,
                'full_name' => $user->name,
                'email' => $user->email,
                'country' => optional($user->registration->country)->name,
                'logo' => public_path('images/stalinks.png')
            ]
        ]);

        $items = [
            (new InvoiceItem())->title('Wallet Credit')->pricePerUnit($payload->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->net_amount->value),
            (new InvoiceItem())->title('Paypal Fee')->pricePerUnit($payload->purchase_units[0]->payments->captures[0]->seller_receivable_breakdown->paypal_fee->value)
        ];

        $invoice = Invoice::make()
            ->series('STAL')
            ->sequence($payload->invoice_id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->filename('STAL-' . $payload->invoice_id)
            ->date(now())
            ->dateFormat('m-d-Y')
            ->seller($this->client)
            ->buyer($customer)
            ->addItems($items)
            ->save('local');

        return $invoice->url();
    }

    public function generateSellerProof($seller, $backlink_ids, $totalBacklinkAmount, $id, $fee)
    {
        $user = auth()->user();

        $customer = new Party([
            'custom_fields' => [
                'full_name' => $seller->name,
                'email' => $seller->email,
                'backlinks' => $backlink_ids,
                'total' => $totalBacklinkAmount
            ]
        ]);

        $items = [
            (new InvoiceItem())->title('Gross Amount')->pricePerUnit($totalBacklinkAmount),
            (new InvoiceItem())->title('Paypal Fee')->pricePerUnit($fee)
        ];

        $invoice = Invoice::make()
            ->series('STAL-SELLER-')
            ->sequence($id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->filename('STAL-SELLER-' . $id)
            ->date(now())
            ->dateFormat('m-d-Y')
            ->seller($this->client)
            ->buyer($customer)
            ->template('seller')
            ->addItems($items)
            ->save('local');

        return $invoice->url();
    }

    public function generateWriterProof($writer, $articleIds, $totalArticleAmount, $id, $fee)
    {
        $customer = new Party([
            'custom_fields' => [
                'full_name' => $writer->name,
                'email' => $writer->email,
                'articles' => $articleIds,
                'total' => $totalArticleAmount
            ]
        ]);

        $items = [
            (new InvoiceItem())->title('Gross Amount')->pricePerUnit($totalArticleAmount),
            (new InvoiceItem())->title('Paypal Fee')->pricePerUnit($fee)
        ];

        $invoice = Invoice::make()
            ->series('STAL-WRITER-')
            ->sequence($id)
            ->serialNumberFormat('{SERIES}-{SEQUENCE}')
            ->filename('STAL-WRITER-' . $id)
            ->date(now())
            ->dateFormat('m-d-Y')
            ->seller($this->client)
            ->buyer($customer)
            ->template('writer')
            ->addItems($items)
            ->save('local');

        return $invoice->url();
    }
}
