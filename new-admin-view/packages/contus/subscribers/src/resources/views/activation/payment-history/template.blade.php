<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            max-width: 995px;
            /* margin: auto; */
            /* padding: 5px; */
            font-size: 14px;
            line-height: 18px;
            /* color: #555; */
        }

        .top-section {
            /* background-color: #b2e0eb; */
            padding: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .logo .circle {
            width: 40px;
            height: 40px;
            background-color: #000;
            border-radius: 50%;
            margin-right: 10px;
        }

        .invoice-details {
            text-align: right;
            font-size: 13px;
            margin-top: -40px;
        }

        .invoice-details div {
            margin-bottom: 3px;
        }

        .address-section {
            background-color: #ccc;
            padding: 10px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
        }

        table th {
            color: #00a0c6;
        }

        .total-section {
            margin-top: 20px;
            text-align: right;
        }

        .total-section div {
            margin-bottom: 5px;
        }

        .total-label {
            font-weight: bold;
        }

        .total-amount {
            background-color: #00ACCD;
            color: #fff;
            font-weight: bold;
            padding: 8px;
        }

        .terms {
            margin-top: 20px;
            border-top: 2px solid #00ACCD;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="top-section">
            <div class="logo">
                <img src="{{ $logo }}">
            </div>
            <div class="invoice-details">
                <div>Invoice #{{ $record->id }}</div>
            </div><br><br>
            <div style="margin-top: 20px;">
                <strong>INVOICE TO</strong><br>
                {{ $record->subscriber_detail->first_name .' '. $record->subscriber_detail->last_name }}<br>
                {{ $record->subscriber_detail->address }}<br>
                {{ $record->subscriber_detail->city .', '. $record->subscriber_detail->state .', '. $record->subscriber_detail->zip_code .', '. $record->subscriber_detail->country }}<br>
                {{ $record->subscriber_detail->email }}<br>
                {{ $record->subscriber_detail->phone_number_code .' '. $record->subscriber_detail->phone_number }}
            </div>
            <div class="invoice-details" style="margin-top: -90px;">
                <div><strong>DUE DATE</strong></div>
                <div>{{ $record->created_at->format('d-m-y') }}</div>
                <div><strong>AMOUNT</strong></div>
                <div>{{ explode(' ', $record->payment_currency)[0] .' '. $record->total }}</div>
            </div>
        </div>

        <div class="address-section">
            <div>{{ env('APP_URL') }}</div>
            <div>info@iptvsolutionsgroup.com</div>
            <div>317.123.8765</div>
            <div>123 Alphabet Road, Suite 01, Indianapolis, IN 46260</div>
        </div>

        <table>
            <tr>
                <th>Product Type</th>
                <th style="text-align:right;">Active From</th>
                <th style="text-align:right;">Active Until</th>
                <th style="text-align:right;">Length</th>
                <th style="text-align:right;">Payment Service</th>
                <th style="text-align:right;">Payment Status</th>
                <th style="text-align:right;">Total</th>
            </tr>
            <tr>
                <td>
                    <strong>{{ $record->product_type }}</strong>
                </td>
                <td style="text-align:right;">
                    {{ $record->start_date ? \Carbon\Carbon::parse($record->start_date)->format('d-m-y') : '-' }}
                </td>
                <td style="text-align:right;">
                    {{ $record->end_date ? \Carbon\Carbon::parse($record->end_date)->format('d-m-y') : '-' }}
                </td>
                <td style="text-align:right;">
                    <?php
                    $date = \Carbon\Carbon::parse($record->start_date);
                    $now = \Carbon\Carbon::parse($record->end_date);
                    $diff = $date->diffInDays($now);
                    ?>
                    {{ $diff }} day
                </td>
                <td style="text-align:right;">{{ $record->payment_service }}</td>
                <td style="text-align:right;">
                    {{ $record->payment_service === 'cash' ? 'paid' :
                        (optional($record->transaction_detail)->status === 'PAYMENT_SUCCESS' ? 'paid' :
                        (optional($record->transaction_detail)->status === 'PAYMENT_FAILED' ? 'cancel' :
                        (optional($record->transaction_detail)->status === null ? 'pending' : 'paid')))
                    }}
                </td>
                <td style="text-align:right;">{{ explode(' ', $record->payment_currency)[0] .' '. $record->total }}</td>
            </tr>

            @if(!empty($devices))
            <tr>
                <td colspan="7" style="padding-top:15px;">
                    <strong>Assigned Devices</strong>
                </td>
            </tr>
            @foreach($devices as $device)
            <tr>
                <td colspan="5">{{ $device['device_name'] }}</td>
                <td colspan="2" style="text-align:right;">
                    @if($device['price'] == 0)
                    Free
                    @else
                    {{ explode(' ', $record->payment_currency)[0] .' '. $device['price'] }}
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </table>

        <div class="total-section">
            <div class="total-amount">Total {{ explode(' ', $record->payment_currency)[0] .' '. $record->total }}</div>
        </div>

        <div class="terms">
            <div>
                <strong>Payment Options</strong><br>
                Paypal<br>
                Credit Card
            </div>
        </div>
    </div>
</body>

</html>