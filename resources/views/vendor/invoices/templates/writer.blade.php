<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $invoice->name }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css" media="screen">
        html {
            font-family: sans-serif;
            line-height: 1.15;
            margin: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
            font-size: 10px;
            margin: 36pt;
        }

        h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        strong {
            font-weight: bolder;
        }

        img {
            vertical-align: middle;
            border-style: none;
        }

        table {
            border-collapse: collapse;
        }

        th {
            text-align: inherit;
        }

        h4, .h4 {
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        h4, .h4 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            /*border-top: 1px solid #dee2e6;*/
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 10px solid #1a8296;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .mt-5 {
            margin-top: 3rem !important;
        }

        .pr-0,
        .px-0 {
            padding-right: 0 !important;
        }

        .pl-0,
        .px-0 {
            padding-left: 0 !important;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-uppercase {
            text-transform: uppercase !important;
        }

        * {
            font-family: "DejaVu Sans";
        }

        body, h1, h2, h3, h4, h5, h6, table, th, tr, td, p, div {
            line-height: 1.1;
        }

        .party-header {
            font-size: 1.5rem;
            font-weight: 400;
        }

        .total-amount {
            font-size: 12px;
            font-weight: 700;
        }

        .border-0 {
            border: none !important;
        }
    </style>
</head>

    <body>
        {{-- Header --}}
        <table class="table mt-5">
            <tbody>
                <tr>
                    <td class="border-0 pl-0" width="70%">
                        <h4 class="text-uppercase">
                            <strong>STA LINK, LTD.</strong>
                        </h4>
                        <p>
                            Unit 912, 9/F, Two Harbourfront <br>
                            22 Tak Fung Street, Hunghom <br>
                            Kowloon, Hong Kongw
                        </p>
                    </td>
                    <td class="border-0 pl-0">
{{--                        <img src="{{ $invoice->buyer->custom_fields['logo'] }}" alt="">--}}
                        <h4 class="text-uppercase" style="color: #1a8296">
                            <strong>PROOF OF PAYMENT</strong>
                        </h4>
{{--                        <p>{{ __('invoices::invoice.serial') }} <strong>{{ $invoice->getSerialNumber() }}</strong></p>--}}
{{--                        <p>Date: <strong>{{ $invoice->getDate() }}</strong></p>--}}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Seller - Buyer --}}
        <table class="table">
            <thead>
                <tr>
                    <th class="border-0 pl-0" width="58.5%">
                        <h2>Payment Sent To</h2>
                    </th>
                    <th class="border-0" width="3%"></th>
                    <th class="border-0 pl-0">
{{--                        <h2>{{ __('invoices::invoice.buyer') }}</h2>--}}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-0">
{{--                        @if($invoice->buyer->custom_fields['company'])--}}
{{--                            <p><strong>Company: </strong> {{ $invoice->buyer->custom_fields['company'] }}</p>--}}
{{--                        @endif--}}

                        @if($invoice->buyer->custom_fields['full_name'])
                            <p><strong>Name: </strong> {{ $invoice->buyer->custom_fields['full_name'] }}</p>
                        @endif

                        @if($invoice->buyer->custom_fields['email'])
                            <p><strong>Email: </strong> {{ $invoice->buyer->custom_fields['email'] }}</p>
                        @endif

{{--                        @if($invoice->buyer->custom_fields['country'])--}}
{{--                            <p><strong>Country: </strong> {{ $invoice->buyer->custom_fields['country'] }}</p>--}}
{{--                        @endif--}}
                        <p><strong>Payment Type: </strong> Articles</p>
                        <p><strong>Payment Status: </strong> Completed</p>
                    </td>
                    <td class="border-0"></td>
                    <td class="px-0">
{{--                        <p><strong>Serial: </strong> {{ $invoice->getSerialNumber() }}</p>--}}

{{--                        <p><strong>Date: </strong> {{ $invoice->getDate() }}</p>--}}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Table --}}
        <table class="table">
            <thead>
                <tr style="height: 200px; background-color: #1a8296; color: #ffffff">
                    <th scope="col" class="border-0 pl-0" width="15%">Your Payment</th>
                    <th scope="col" class="text-center border-0" width="3%"></th>
                    <th scope="col" class="text-right border-0" width="15%"></th>
                    <th scope="col" class="text-right border-0"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td class="pl-0">
                        <strong>{{ $item->title }}</strong>
                    </td>
                    <td></td>
                    <td>
                        -{{ $invoice->formatCurrency($item->price_per_unit) }}
                    </td>
                    <td></td>
                </tr>
            @endforeach
                <tr>
                    <td class="pl-0"><strong>Net Amount</strong></td>
                    <td></td>
                    <td>-{{ $invoice->formatCurrency($invoice->total_amount) }}</td>
                    <td> </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <p><strong>Payment for</strong></p>
        @foreach($invoice->buyer->custom_fields['articles'] as $article)
            Article ID {{$article}} <br>
        @endforeach

        <script type="text/php">
            if (isset($pdf) && $PAGE_COUNT > 1) {
                $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Verdana");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
            }
        </script>
    </body>
</html>
