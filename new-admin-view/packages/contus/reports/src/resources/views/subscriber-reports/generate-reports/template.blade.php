<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Report</title>
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
                <div>Report #{{ $report->id }}</div>
                <div><strong>Report Name:</strong> {{ $report->report_name ?? 'Subscriber Report' }}</div>
                <div><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }}</div>
            </div>
        </div>
    </div>

    <div class="address-section">
        <div>{{ env('APP_URL') }}</div>
        <div>info@iptvsolutionsgroup.com</div>
        <div>317.123.8765</div>
        <div>123 Alphabet Road, Suite 01, Indianapolis, IN 46260</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Field Name</th>
                <th>Filter Type</th>
                <!-- <th></th> -->
            </tr>
        </thead>

        <tbody>
            @forelse($record as $row)
                <tr>
                    @foreach($fields as $field)
                        <td>{{ ucfirst(str_replace('_', ' ', $field)) }}: {{ $row[$field] ?? '' }}</td>
                    @endforeach
                    <td>{{ implode(', ', $filters) ?: 'None' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($fields) }}">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>









    <!-- <div style="text-align:center; margin-bottom: 16px;">
        @if(!empty($logo))
            <img src="{{ $logo }}" alt="Logo" style="max-height:80px;">
        @endif
        <h2 style="margin: 8px 0;">{{ $report->report_name ?? 'Subscriber Report' }}</h2>
    </div>

    <p><strong>Filters Applied:</strong> {{ implode(', ', $filters) ?: 'None' }}</p>

    <table border="1" cellspacing="0" cellpadding="6" width="100%">
        <thead>
            <tr>
                @foreach($fields as $field)
                    <th>{{ ucfirst(str_replace('_', ' ', $field)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($record as $row)
                <tr>
                    @foreach($fields as $field)
                        <td>{{ $row[$field] ?? '' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($fields) }}">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table> -->

</body>

</html>