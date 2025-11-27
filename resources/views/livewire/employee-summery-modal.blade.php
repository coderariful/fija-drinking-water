<div class="position-relative" x-data="{type:@entangle('filterType').live}">
    <div class="mb-3">
        @if($user)
            <div class="pt-2">
                <table class="table text-center mb-2 table-bordered">
                    <tr>
                        <th class="p-0">Employee Name</th>
                        <th class="p-0">Employee Phone</th>
                    </tr>
                    <tr>
                        <td class="p-0">{{$user->name}}</td>
                        <td class="p-0">{{$user->phone}}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
    <div class="position-absolute w-100 py-5" wire:loading style="z-index:9999;background:#ffffff7f">
        <div class="d-flex my-5 justify-content-center w-100">
            <i class="fa fa-circle-o-notch fa-spin fa-4x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="my-3">
        <div class="input-group">
            <select class="form-control" wire:model.live="customer_id" aria-label="All Customer">
                <option selected value="">{{trans('All Customer')}}</option>
                @foreach($customers as $_customer)
                    <option value="{{$_customer?->id}}">{{$_customer?->name}} - {{$_customer?->phone}}</option>
                @endforeach
            </select>
            <select class="form-control" x-model="type" aria-label="All Customer">
                <option value="day">Day Wise</option>
                <option value="month">Month Wise</option>
                <option value="date">Date Wise</option>
            </select>
            <span class="input-group-text" x-show="type==='day'">Day</span>
            <select class="form-control" wire:model.live="day" x-show="type==='day'" aria-label="Day">
                @foreach($days as $dayKey => $dayName)
                    <option value="{{$dayKey}}">{{$dayName}}</option>
                @endforeach
            </select>
            <span class="input-group-text" x-show="type==='month'">Year</span>
            <select class="form-control" wire:model.live="year" x-show="type==='month'" aria-label="Year">
                @for($y = 2022; $y <= today()->format('Y'); $y++)
                    <option value="{{$y}}">{{$y}}</option>
                @endfor
            </select>
            <span class="input-group-text" x-show="type==='month'">Month</span>
            <select class="form-control" wire:model.live="month" x-show="type==='month'" aria-label="Month">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{date('n', mktime(0, 0, 0, $m, 1, date('Y')))}}">
                        {{date('F', mktime(0, 0, 0, $m, 1, date('Y')))}}
                    </option>
                @endfor
            </select>
            <span class="input-group-text" x-show="type==='date'">From</span>
            <input type="date" class="form-control" wire:model.live="start_date" x-show="type==='date'"
                aria-label="From" />
            <span class="input-group-text" x-show="type==='date'">To</span>
            <input type="date" class="form-control" wire:model.live="end_date" x-show="type==='date'" aria-label="To" />
        </div>
    </div>
    <div class="mb-3 border text-center p-2">
        <strong>{{trans('Total Due')}}:</strong> {{$total_due}}
        <span>&nbsp;|&nbsp;</span>
        @if(!empty($customer_id))
            <strong>{{trans('Customer')}}:</strong> {{$customer->name}} - {{$customer->phone}} - {{$customer->address}}
        @else
            <strong>{{trans('Number of Customer')}}:</strong> {{count($customers)}}
        @endif
    </div>
    <div class="mb-3 border">
        @if(!empty($showCurrentFilter))
            <p class="mb-0 mt-2 text-center">
                <strong class="text-indigo">{{$showCurrentFilter}}</strong>
            </p>
        @endif
        <div class="p-2 d-flex flex-wrap flex-md-nowrap justify-content-around" style="gap: 0.5rem">
            <div class="text-center bg-light px-3 py-2 rounded" style="flex: 1 1 auto">
                <p class="h3">{{$jar_stock}}</p>
                <strong>{{trans('Jar Stock')}}</strong>
            </div>
            <div class="text-center bg-light px-3 py-2 rounded" style="flex: 1 1 auto">
                <p class="h3">{{$jar_in_count}}</p>
                <strong>{{trans('Jar Sale')}}</strong>
            </div>
            <div class="text-center bg-light px-3 py-2 rounded" style="flex: 1 1 auto">
                <p class="h3">{{round($sell_amount, 2)}}</p>
                <strong>{{trans('Sell Amount')}}</strong>
            </div>
            <div class="text-center bg-light px-3 py-2 rounded" style="flex: 1 1 auto">
                <p class="h3">{{round($collection_amount, 2)}}</p>
                <strong>{{trans('Collection')}}</strong>
            </div>
            <div class="text-center bg-light px-3 py-2 rounded" style="flex: 1 1 auto">
                <p class="h3">{{max(round($sell_amount - $collection_amount, 2), 0)}}</p>
                <strong>{{trans('Due Amount')}}</strong>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-3">
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
                                @php
                                    $history = $histories?->first()
                                @endphp
                                @if(count($histories) > 1)
                                    <td class="text-center align-middle" rowspan="{{count($histories) + 1}}">{{$loop->iteration}}</td>
                                    <td class="text-center py-0" rowspan="{{count($histories) + 1}}">{{$history?->customer?->phone}}
                                    </td>
                                    <td class="text-left py-0" rowspan="{{count($histories) + 1}}">
                                        {{$history?->customer?->name}}<br>{{$history?->customer?->address}}
                                    </td>
                                    <td class="text-center py-0" rowspan="{{count($histories) + 1}}">
                                        {{round($history?->customer?->jar_rate, 2)}}/-
                                    </td>
                                @else
                                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                                    <td class="text-center py-0">{{$history?->customer?->phone}}</td>
                                    <td class="text-left py-0">
                                        {{$history?->customer?->name}}<br>{{$history?->customer?->address}}
                                    </td>
                                    <td class="text-center py-0">{{round($history?->customer?->jar_rate, 2)}}/-</td>
                                    <td class="text-center py-0">{{formatDate($history->created_at, DATE_FORMAT)}}</td>
                                    <td class="text-center py-0">{{$history->in_quantity}}</td>
                                    <td class="text-center py-0">{{$history->out_quantity}}</td>
                                    <td class="text-center py-0">{{$history->stock_qty}}</td>
                                    <td class="text-center py-0">{{round($history->paid_amount ?? 0, 2)}}</td>
                                    <td class="text-center py-0">{{round($history->due_till_date ?? 0, 2)}}</td>
                                    <td class="text-center py-0">{{$history->note ?? '-'}}</td>
                                @endif
                            </tr>
                            @if(count($histories) > 1)
                                @foreach($histories->sortBy('created_at') as $history)
                                    <tr>
                                        <td class="text-center py-0">{{formatDate($history->created_at, DATE_FORMAT)}}</td>
                                        <td class="text-center py-0">{{$history->in_quantity}}</td>
                                        <td class="text-center py-0">{{$history->out_quantity}}</td>
                                        <td class="text-center py-0">{{$history->stock_qty}}</td>
                                        <td class="text-center py-0">{{round($history->paid_amount ?? 0, 2)}}</td>
                                        <td class="text-center py-0">{{round($history->due_till_date ?? 0, 2)}}</td>
                                        <td class="text-center py-0">{{$history->note ?? '-'}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No data</td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="10" class="text-center">No data</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="text-center">
        @if($user)
            <form action="{{ route('print.sales-list', $user->id) }}" method="GET" target="_blank">
                <input type="hidden" name="customer_id" value="{{ $customer_id }}">
                <input type="hidden" name="filter" value="{{ $filterType }}">
                @foreach([$start_date, $end_date] as $dt)
                    <input type="hidden" name="date[]" value="{{ $dt }}">
                @endforeach
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="day" value="{{ $day }}">
                <input type="hidden" name="view" value="on">
                <button type="submit" class="btn btn-danger">{{ trans('PRINT') }}</button>
            </form>
        @endif
    </div>
</div>
