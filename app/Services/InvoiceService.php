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
        $customer = new Party([
            'name'          => $payload->purchase_units[0]->shipping->name->full_name,
            'address'       => $payload->purchase_units[0]->shipping->address->address_line_1,
            'custom_fields' => [
                'postal code' => $payload->purchase_units[0]->shipping->address->postal_code,
                'company' => auth()->user()->registration->company_name
            ]
        ]);

        $item = (new InvoiceItem())->title('Wallet Credit')->pricePerUnit($payload->purchase_units[0]->payments->captures[0]->amount->value);

        $invoice = Invoice::make()
            ->seller($this->client)
            ->buyer($customer)
            ->addItem($item)
            ->save('local');

        return $invoice->url();
    }
}
