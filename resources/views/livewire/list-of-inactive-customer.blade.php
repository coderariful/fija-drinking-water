<div class="row" x-data="{}">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
{{--                <form action="{{route('print.customer-list')}}">--}}
                    <div class="row">
                        <div class="col-md-6 col-sm-12 d-flex justify-content-between align-items-center">
                            <h6 class="card-title">{{$title}}</h6>
                            {{-- <button type="button" class="btn btn-danger" onclick="return confirm('{{trans('Are you sure?')}}') || event.stopImmediatePropagation()" wire:click="sendToAll">{{trans('Send SMS to All')}}</button> --}}
                            @if(auth_user()->user_type==0)
                            <select class="form-control w-25" wire:model="employee_id" name="employee_id">
                                <option value="">All</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="input-group row">
                                <input type="date" class="form-control w-25" wire:model="start_date" name="start_date">
                                <input type="date" class="form-control w-25" wire:model="end_date" name="end_date">
                                <input type="text" class="form-control w-25" placeholder="Search Customer by Name or Phone" wire:model.debounce="keyword" name="keyword">
                            </div>
                        </div>
                    </div>
                {{-- </form> --}}
            </div>
            <div class="card-body">
                <div class="table-responsive style-scroll">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">{{__('S/N')}}</th>
                            <th scope="col">{{ __('Employee') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Address') }}</th>
                            <th scope="col" class="text-center">{{ __('Jar Rate') }}</th>
                            <th scope="col" class="text-center">{{ __('Stock') }}</th>
                            <th scope="col" class="text-center">{{ __('Due') }}</th>
                            <th scope="col" class="text-center">{{ __('Last Sale') }}</th>
                            <th scope="col" class="text-center">{{ __('Last Payment') }}</th>
                            <th scope="col">{{ __('Issue Date') }}</th>
                            <th scope="col">{{ __('Billing Type') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <th class="text-center">{{ paginationIndex($customers, $loop->iteration) }}</th>
                                <td>{{ $customer->user?->name??'-'}}</td>
                                <td>{{ $customer->name??''}}</td>
                                <td>{{ $customer->phone??'' }}</td>
                                <td>
                                    <span title="{{$customer->address}}">{{ str($customer->address??'')->limit(20) }}</span>
                                </td>
                                <td class="text-center">{{ $customer->jar_rate??'-' }}</td>
                                <td class="text-center">{{ $customer->jar_stock }}</td>
                                <td class="text-center">{{ $customer->due_amount }}</td>
                                <td class="text-center">{{ $customer->sales->first()?->created_at->format('d-M-y') ?? '-' }}</td>
                                <td class="text-center">{{ $customer->payments->first()?->created_at->format('d-M-y') ?? '-' }}</td>
                                <td>{{ formatDate($customer->issue_date, 'd-M-y')??'' }}</td>
                                <td>{{ str($customer->billing_type??'')->upper() }}</td>
                                <td>
                                    @if($customer->status == CUSTOMER_PENDING)
                                        <span class="text-warning py-0 text-center">PENDING</span>
                                    @elseif($customer->status == CUSTOMER_APPROVED)
                                        <span class="text-success py-0 text-center">APPROVED</span>
                                    @elseif($customer->status == CUSTOMER_REJECTED)
                                        <span class="text-danger py-0 text-center">REJECTED</span>
                                    @endif

                                </td>
                                <td nowrap>
                                    {{--<button type="button"  class="btn btn-sm btn-warning btn-circle" title="Add Sell" data-toggle="modal" data-target="#sellModal" wire:click="$emitTo('sell-modal', 'open-modal', {{$customer->id}})">
                                        <i class="material-icons">shopping_basket</i>
                                    </button>--}}
                                    {{--<button type="button"  class="btn btn-sm btn-warning btn-circle" title="Payment" data-toggle="modal" data-target="#paymentModal" wire:click="$emitTo('payment-modal', 'open-modal', {{$customer->id}})">
                                        <i class="material-icons">account_balance_wallet</i>
                                    </button>--}}
                                    <button type="button" class="btn btn-sm btn-info btn-circle" title="Purchase History" data-toggle="modal" data-target="#historyModal" wire:click="$emitTo('purchase-history-modal', 'open-modal', {{$customer->id}})">
                                        <i class="material-icons">assignment</i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info btn-circle" title="Send SMS" data-toggle="modal" data-target="#smsModal" wire:click="$emitTo('admin.sms-modal', 'open-modal', {{$customer->id}})">
                                        <i class="material-icons">send</i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 text-center" colspan="11">No customer found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer justify-content-end">
                {{ $customers->links('livewire::bootstrap') }}
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smsModalLabel">Send SMS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:admin.sms-modal/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Purchase History</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:purchase-history-modal/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            $(document).on('sms-sent', function() {
                $("#sms_message").val("");
            });
        });
    </script>
@endpush
