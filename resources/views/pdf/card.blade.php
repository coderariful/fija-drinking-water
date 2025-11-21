<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Card</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('backend/assets/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}">
    <style>
        * {
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        @page {
            margin: 10pt 30pt 10pt 0;
        }
        body {
            @if(request('view')=='on')
            width: 8.27in;
            margin: 20pt auto;
            @endif
        }
        .table-column {
            line-height: 1;
            border: 1px solid #1b1b1b !important;
            padding-top: 2px !important;
            padding-bottom: 2px !important;
        }
        @media print {
            table, .table, .table-bordered, .table th, .table td {
                background: transparent !important;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <img src="{{asset('logo.png')}}" alt="watermark" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);opacity:0.1;z-index:-999;">
    <div class="mb-4">
        <div class="border px-1 float-left rounded" style="line-height:1;padding:2px 2px 1px">
            {!! join("<br>", $dispenser) !!}
        </div>
        <div class="border px-1 float-right rounded"  style="line-height:1;padding:2px 2px 1px">
            {{round($customer->jar_rate, 2)}} Tk/Jar
        </div>
        <div class="text-center my-3 mx-auto" style="width: max-content">
            <div class="float-left mr-3">
                <img src="{{asset('logo.png')}}" alt="logo" width="100"/>
            </div>
            <div class="float-left pt-3">
                <h3 class="mb-0">Fija Drinking Water</h3>
                <h6 class="mb-0">5, Pal Para Road, Shib bari, Khulna</h6>
                <strong>01958-448290, 01958448291, bKash: 01715-552398</strong>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="mt-3 text-center">
            <p class="mb-0">
                <span class="mr-3"><strong>Name:</strong> {{$customer->name}}</span>
                <span><strong>Phone:</strong> {{$customer->phone}}</span>
            </p>
            <p class="mb-0">
                <strong>Address:</strong> {{$customer->address}}
            </p>
        </div>
    </div>
    <div class="mb-3">
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th class="align-middle text-center table-column" rowspan="2">{{trans('Date')}}</th>
                <th class="align-middle text-center table-column" colspan="3">{{trans('Jar')}}</th>
                <th class="align-middle text-center table-column" colspan="2">{{trans('Amount (Tk)')}}</th>
                <th class="align-middle text-center table-column" rowspan="2">{{trans('Comment')}}</th>
            </tr>
            <tr>
                <th class="align-middle text-center table-column">{{trans('Issue')}}</th>
                <th class="align-middle text-center table-column">{{trans('Return')}}</th>
                <th class="align-middle text-center table-column">{{trans('Stock')}}</th>
                <th class="align-middle text-center table-column">{{trans('Paid')}}</th>
                <th class="align-middle text-center table-column">{{trans('Due')}}</th>
            </tr>
            </thead>
            <tbody>
            @if($customer)
                @php
                    $due_amount = $previous_due;
                @endphp
                @forelse($histories as $history)
                    @php($due_amount += $history->sale?->total_cost - $history->payment?->amount)
                    @if($history->product_type==PRODUCT_DISPENSER)
                        @continue
                    @endif
                    <tr>
                        <td class="text-center table-column">{{formatDate($history->created_at, DATE_FORMAT)}}</td>
                        @if($history->product_type !== PRODUCT_DISPENSER)
                            <td class="text-center table-column">{{$history->in_quantity}}</td>
                            <td class="text-center table-column">{{$history->out_quantity}}</td>
                            <td class="text-center table-column">{{$history->jar_stock}}</td>
                        @else
                            <td class="text-center table-column" colspan="3">{{$history->in_quantity}} &times; {{str($history->product_type)->title}} - {{$history->rate}} Tk</td>
                        @endif
                        <td class="text-center table-column">{{round($history->payment?->amount ?? 0.00, 2)}}</td>
                        <td class="text-center table-column">{{round($due_amount ?? 0.00, 2)}}</td>
                        <td class="text-center table-column">{{$history->note ?? '-'}}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No data</td></tr>
                @endforelse
            @else
                <tr><td colspan="7" class="text-center">No data</td></tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
