<div x-data="{type:@entangle('filterType').live}">
    <div class="mb-3">
        @if($customer)
            <div class="pt-2">
                <table class="table text-center mb-2 table-bordered">
                    <tr>
                        <th class="p-0">{{trans('Customer Name')}}</th>
                        <th class="p-0">{{trans('Customer Phone')}}</th>
                        <th class="p-0">{{trans('Jar Rate')}}</th>
                    </tr>
                    <tr>
                        <td class="p-0">{{$customer->name}}</td>
                        <td class="p-0">{{$customer->phone}}</td>
                        <td class="p-0">{{$customer->jar_rate}}/-</td>
                    </tr>
                </table>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th class="py-0">{{trans('Dispensers')}}</th>
                        <td class="py-0" width="100%">
                            @php $dispenserList = $customer->dispenserAll()->join(', ') @endphp
                            {{ !empty($dispenserList) ? $dispenserList : trans('No Dispenser')  }}
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
    <div class="my-3">
        <div class="input-group">
            {{--<select class="form-control" x-model="type">
                <option value="month">{{trans('Month Wise')}}</option>
                <option value="date">{{trans('Date Wise')}}</option>
            </select>--}}
            <span class="input-group-text" x-show="type=='month'">Year</span>
            <select class="form-control" wire:model.live="year" x-show="type=='month'">
                @for($y=2022; $y <= today()->format('Y'); $y++)
                    <option value="{{$y}}">{{$y}}</option>
                @endfor
            </select>
            <span class="input-group-text" x-show="type=='month'">{{trans('Month')}}</span>
            <select class="form-control" wire:model.live="month" x-show="type=='month'">
                @for($m=1; $m<=12; $m++)
                    <option value="{{date('n', mktime(0,0,0,$m, 1, date('Y')))}}">{{date('F', mktime(0,0,0,$m, 1, date('Y')))}}</option>
                @endfor
            </select>
            <span class="input-group-text" x-show="type=='date'">{{trans('From')}}</span>
            <input type="date" class="form-control" wire:model.live="start_date" x-show="type=='date'"/>
            <span class="input-group-text" x-show="type=='date'">{{trans('To')}}</span>
            <input type="date" class="form-control" wire:model.live="end_date" x-show="type=='date'"/>
        </div>
    </div>
    <div class="mb-3 history-table position-relative">
        <x-admin.table-processing-indicator bg-light="true" middle="true"/>
        <table class="table table-sm table-bordered">
            <thead>
            <tr>
                <th class="align-middle text-center" rowspan="2">{{trans('Date')}}</th>
                <th class="align-middle text-center" colspan="3">{{trans('Jar')}}/{{trans('Jar')}}</th>
                <th class="align-middle text-center" colspan="2">{{trans('Amount')}}</th>
                <th class="align-middle text-center" rowspan="2">{{trans('Comment')}}</th>
            </tr>
            <tr>
                <th class="align-middle text-center">{{trans('Issue')}}</th>
                <th class="align-middle text-center">{{trans('Return')}}</th>
                <th class="align-middle text-center">{{trans('Stock')}}</th>
                <th class="align-middle text-center">{{trans('Paid')}}</th>
                <th class="align-middle text-center">{{trans('Due')}}</th>
            </tr>
            </thead>
            <tbody>
            @if($customer)
                @php
                    $due_amount = $previous_due;
                @endphp
                @forelse($histories as $history)
                    @php($due_amount += $history->total_amount - $history->paid_amount)
                    {{-- @if($history->product_type==PRODUCT_DISPENSER) @continue @endif --}}
                    <tr>
                        <td class="text-center">
                            <x-editable-column model="date_created" method="saveDateUpdate" :item-id="$history->id" :value="formatDate($history->created_at, DATE_FORMAT)" input-type="date"/>
                        </td>
                        @if($history->product_type !== PRODUCT_DISPENSER)
                        <td class="text-center">
                             <x-editable-column model="in_quantity" method="saveIssueUpdate" :item-id="$history->id" :value="$history->in_quantity"  input-type="number"/>
                        </td>
                        <td class="text-center">
                            <x-editable-column model="out_quantity" method="saveReturnUpdate" :item-id="$history->id" :value="$history->out_quantity"  input-type="number"/>
                        </td>
                        @else
                        <td class="text-center" colspan="2">{{$history->in_quantity}} &times; {{str($history->product_type)->title}} - {{$history->rate}} Tk</td>
                        @endif
                        <td class="text-center">{{$history->stock_qty}}</td>
                        <td class="text-center">
                            <x-editable-column model="payment" method="savePaymentUpdate" :item-id="$history->id" :value="round($history->paid_amount ?? 0)"  input-type="number" :input-style="['width:70px']"/>
                        </td>
                        <td class="text-center">{{round($due_amount) ?? 0}}</td>
                        <td class="text-center">{{$history->note ?? '-'}}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">{{trans('No data')}}</td></tr>
                @endforelse
            @else
                <tr><td colspan="7" class="text-center">{{trans('No data')}}</td></tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="text-center">
        @if($customer)
        <a href="{{route('print.card', ['customer'=>$customer,'filter'=>$filterType,'date'=>[$start_date,$end_date],'month'=>$month, 'year'=>$year])}}" class="btn btn-danger" target="_blank">{{trans("PRINT")}}</a>
        @endif
    </div>
</div>
