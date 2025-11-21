<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Employer Summery</title>

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
            margin: 10pt 30pt 10pt 10pt;
        }
        body {
            @if(request('view')=='on')
            width: 8.27in;
            margin: 20pt auto;
            @else
            margin: 20pt;
            @endif
        }
        .table-column {
            line-height: 1;
            border: 1px solid #1b1b1b !important;
            padding-top: 2px !important;
            padding-bottom: 2px !important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <img src="{{asset('logo.png')}}" alt="watermark" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);opacity:0.1;z-index:-999;">
    <div class="mb-4">
        <div style="width: max-content; margin: 0 auto">
            <div class="float-left mr-3">
                <img src="{{asset('logo.png')}}" alt="logo" width="50"/>
            </div>
            <div class="float-left pt-3">
                <h3 class="mb-0">Fija Drinking Water</h3>
                <h6 class="mb-0">5, Pal Para Road, Shib bari, Khulna</h6>
                <strong>01958-448290, 01958448291, bKash: 01715-552398</strong>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="mt-3 text-center">
            <span class="mx-3"><strong>Employee Name:</strong> {{$user->name}}</span>
            <span class="mx-3"><strong>Phone:</strong> {{$user->phone}}</span>
        </div>
        <div class="mt-3 text-center">
            <strong class="mx-3">{{__("Total Due")}}:</strong> {{$total_due}}
            @if(!empty($customer_id))
                <strong class="mx-3">{{trans('Customer')}}:</strong> {{$customer->name}} - {{$customer->phone}} - {{$customer->address}}
            @else
                <strong class="mx-3">{{trans('Number of Customer')}}:</strong> {{count($customers)}}
            @endif
        </div>
    </div>
    <div class="mb-3">
        @if(!empty($showCurrentFilter))
            <p class="mb-0 mt-2 text-center">
                <strong class="text-indigo">{{$showCurrentFilter}}</strong>
            </p>
        @endif

        <table class="table table-bordered">
            <tr>
                <td>
                    <div class="text-center">
                        <p class="h3 mb-0">{{($jar_in_count+$jar_in_previous)-($jar_out_count+$jar_out_previous)}}</p>
                        <strong>{{trans('Jar Stock')}}</strong>
                    </div>
                </td>
                <td>
                    <div class="text-center">
                        <p class="h3 mb-0">{{$jar_in_count}}</p>
                        <strong>{{trans('Jar Sale')}}</strong>
                    </div>
                </td>
                <td>
                    <div class="text-center">
                        <p class="h3 mb-0">{{round($sell_amount, 2)}}/-</p>
                        <strong>{{trans('Sell Amount')}}</strong>
                    </div>
                </td>
                <td>
                    <div class="text-center">
                        <p class="h3 mb-0">{{round($collection_amount, 2)}}/-</p>
                        <strong>{{trans('Collection')}}</strong>
                    </div>
                </td>
                <td>
                    <div class="text-center">
                        <p class="h3 mb-0">{{round($sell_amount-$collection_amount, 2)}}/-</p>
                        <strong>{{trans('Due Amount')}}</strong>
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <div class="mb-3">
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th class="align-middle text-center py-0" rowspan="2">{{trans('S/N')}}</th>
                <th class="align-middle text-center py-0" colspan="3">{{trans('Customer')}}</th>
                <th class="align-middle text-center py-0" rowspan="2">{{trans('Date')}}</th>
                <th class="align-middle text-center py-0" colspan="3">{{trans('Jar')}}</th>
                <th class="align-middle text-center py-0" colspan="2">{{trans('Amount')}}</th>
                <th class="align-middle text-center py-0" rowspan="2">{{trans('Comment')}}</th>
            </tr>
            <tr>
                <th class="align-middle text-center py-0">{{trans('Phone')}}</th>
                <th class="align-middle text-center py-0">{{trans('Name & Address')}}</th>
                <th class="align-middle text-center py-0">{{trans('Jar Rate')}}</th>
                <th class="align-middle text-center py-0">{{trans('Issue')}}</th>
                <th class="align-middle text-center py-0">{{trans('Return')}}</th>
                <th class="align-middle text-center py-0">{{trans('Stock')}}</th>
                <th class="align-middle text-center py-0">{{trans('Paid')}}</th>
                <th class="align-middle text-center py-0">{{trans('Due')}}</th>
            </tr>
            </thead>
            <tbody>
            @if($user)
                @forelse($groups->sortDesc() as $histories)
                    <tr>
                        @php($history = $histories?->first())
                        @if(count($histories)>1)
                            <td class="text-center align-middle" rowspan="{{count($histories)+1}}">{{$loop->iteration}}</td>
                            <td class="text-center py-0" rowspan="{{count($histories)+1}}">{{$history?->customer?->phone}}</td>
                            <td class="text-left py-0" rowspan="{{count($histories)+1}}">{{$history?->customer?->name}}<br>{{$history?->customer?->address}}</td>
                            <td class="text-center py-0" rowspan="{{count($histories)+1}}">{{round($history?->customer?->jar_rate, 2)}}/-</td>
                        @else
                            <td class="text-center align-middle">{{$loop->iteration}}</td>
                            <td class="text-center py-0">{{$history?->customer?->phone}}</td>
                            <td class="text-left py-0">{{$history?->customer?->name}}<br>{{$history?->customer?->address}}</td>
                            <td class="text-center py-0">{{round($history?->customer?->jar_rate, 2)}}/-</td>
                            <td class="text-center py-0">{{formatDate($history->created_at, DATE_FORMAT)}}</td>
                            <td class="text-center py-0">{{$history->in_quantity}}</td>
                            <td class="text-center py-0">{{$history->out_quantity}}</td>
                            <td class="text-center py-0">{{$history->stock_qty}}</td>
                            <td class="text-center py-0">{{round($history->payment?->amount ?? 0, 2)}}</td>
                            <td class="text-center py-0">{{round($history->due_till_date ?? 0, 2)}}</td>
                            <td class="text-center py-0">{{$history->note ?? '-'}}</td>
                        @endif
                    </tr>
                    @if(count($histories)>1)
                        @foreach($histories->sortBy('created_at') as $history)
                            <tr>
                                <td class="text-center py-0">{{formatDate($history->created_at, DATE_FORMAT)}}</td>
                                <td class="text-center py-0">{{$history->in_quantity}}</td>
                                <td class="text-center py-0">{{$history->out_quantity}}</td>
                                <td class="text-center py-0">{{$history->stock_qty}}</td>
                                <td class="text-center py-0">{{round($history->payment?->amount ?? 0, 2)}}</td>
                                <td class="text-center py-0">{{round($history->due_till_date ?? 0, 2)}}</td>
                                <td class="text-center py-0">{{$history->note ?? '-'}}</td>
                            </tr>
                        @endforeach
                    @endif
                @empty
                    <tr><td colspan="9" class="text-center">No data</td></tr>
                @endforelse
            @else
                <tr><td colspan="9" class="text-center">No data</td></tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
