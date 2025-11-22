<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Card</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('backend/assets/dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/css/style.css')}}">
    <style>
        @page {
            margin: 20px;
            padding: 10px;
        }
        @media print {
            @page {
                size: landscape;
            }
            table.customer-list table, table.customer-list th, table.customer-list td {
                border: 1px solid #000 !important;
                border-collapse: collapse;
                background: transparent !important;
                line-height: 1 !important;
                padding: 3px !important;
            }
        }
        @viewport {
            orientation: landscape;
        }
        body {margin: 0 30px 0 0;}
        * { font-family: 'Noto Sans Bengali', sans-serif; }
    </style>
</head>
<body>
<div class="container-fluid">
    <img src="{{asset('logo.png')}}" alt="watermark" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);opacity:0.1;z-index:-999;">
    <div class="text-center">
        <h5>Customer List</h5>
    </div>
    <div class="mb-3">
        <table class="table table-sm table-bordered customer-list">
            <thead>
            <tr>
                <th class="align-middle text-center pt-0">{{trans('S/N')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Issue Date')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Employee')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Customer')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Phone')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Address')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Rate')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Stock')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Due')}}</th>
                <th class="align-middle text-center pt-0">{{trans('Billing Type')}}</th>
                {{--<th class="align-middle text-center pt-0">{{trans('Status')}}</th>--}}
            </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="text-center pt-0">{{$loop->iteration}}</td>
                        <td class="text-center pt-0" nowrap>{{formatDate($customer->created_at, PRINT_DATE_FORMAT)}}</td>
                        <td class="pt-0">{{$customer->user->name}}</td>
                        <td class="pt-0">{{$customer->name}}</td>
                        <td class="pt-0">{{$customer->phone}}</td>
                        <td class="pt-0">{{$customer->address}}</td>
                        <td class="text-right pt-0" nowrap>{{round($customer->jar_rate,2)}}/-</td>
                        <td class="text-center pt-0" nowrap>{{$customer->jar_stock}}</td>
                        <td class="text-right pt-0" nowrap>{{round($customer->due_amount,2)}}/-</td>
                        <td class="pt-0 text-center">{{str($customer->billing_type)->upper}}</td>
                        {{--<td class="pt-0 text-center">{{$customer::STATUS[$customer->status]}}</td>--}}
                    </tr>
                @empty
                    <tr><td colspan="12" class="text-center">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
