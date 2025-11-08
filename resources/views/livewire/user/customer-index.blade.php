<div class="row">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
                <div class="row">
                    <div class="col-md-6 col-sm-12 d-flex justify-content-between align-items-center">
                        <h6 class="card-title">{{$title}}</h6>
                        @if($showDue)
                            <button type="button" class="btn btn-primary" wire:click.prevent="filterDue(false)">{{trans('Show All')}}</button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click.prevent="filterDue(true)">{{trans('Show Due')}}</button>
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="input-group row">
                            <input type="date" class="form-control w-25" wire:model.live="start_date">
                            <input type="date" class="form-control w-25" wire:model.live="end_date">
                            <input type="text" class="form-control w-25" placeholder="Search Customer by Name or Phone" wire:model.live.debounce="keyword">
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive style-scroll">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">{{__('S/N')}}</th>
                            <th scope="col"></th>
                            <th scope="col">{{ __('Employee') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Address') }}</th>
                            <th scope="col" class="text-center">{{ __('Jar Rate') }}</th>
                            <th scope="col" class="text-center">{{ __('Jar Stock') }}</th>
                            <th scope="col" class="text-center">{{ __('Due') }}</th>
                            <th scope="col">{{ __('Issue Date') }}</th>
                            <th scope="col">{{ __('Billing Type') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($customers as $customer)
                            <tr>
                                <th>{{ paginationIndex($customers, $loop->iteration) }}</th>
                                <td nowrap>
                                    <button type="button"  class="btn btn-sm btn-warning btn-circle" title="Add Sell" data-toggle="modal" data-target="#sellModal" wire:click="$dispatchTo('sell-modal', 'open-modal', {{$customer->id}})" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">shopping_basket</i>
                                    </button>
                                    <button type="button"  class="btn btn-sm btn-warning btn-circle" title="Payment" data-toggle="modal" data-target="#paymentModal" wire:click="$dispatchTo('payment-modal', 'open-modal', {{$customer->id}})" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">account_balance_wallet</i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info btn-circle" title="Purchase History" data-toggle="modal" data-target="#historyModal" wire:click="$dispatchTo('purchase-history-modal', 'open-modal', {{$customer->id}})" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">assignment</i>
                                    </button>
                                </td>
                                <td>{{ $customer->user?->name??'-'}}</td>
                                <td>{{ $customer->name??''}}</td>
                                <td>{{ $customer->phone??'' }}</td>
                                <td>
                                    <span title="{{$customer->address}}">{{ str($customer->address??'')->limit(20) }}</span>
                                </td>
                                <td class="text-center">{{ $customer->jar_rate??'-' }}</td>
                                <td class="text-center">{{ $customer->jar_stock }}</td>
                                <td class="text-center">{{ $customer->due_amount }}</td>
                                <td>{{ formatDate($customer->issue_date, DATE_FORMAT) }}</td>
                                <td>{{ str($customer->billing_type??'')->upper() }}</td>
                                <td>
                                    @if($customer->status == CUSTOMER_APPROVED)
                                        <div class="bg-success px-2 py-1 text-center rounded">APPROVED</div>
                                    @elseif($customer->status == CUSTOMER_PENDING)
                                        <div class="bg-warning px-2 py-1 text-center rounded">PENDING</div>
                                    @elseif($customer->status == CUSTOMER_REJECTED)
                                        <div class="bg-danger px-2 py-1 text-center rounded">REJECTED</div>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->status == \App\Models\Customer::PENDING)
                                        <a href="{{route('user.customer.edit',$customer->id)}}" class="btn btn-sm btn-success btn-circle disabled" title="Edit" data-bs-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    @else
                                        <a href="{{route('user.customer.edit',$customer->id)}}" class="btn btn-sm btn-success btn-circle" title="Edit" data-bs-toggle="tooltip" data-placement="top">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    @endif
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
    <div class="modal fade" id="sellModal" tabindex="-1" aria-labelledby="sellModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sellModalLabel">Add Sell</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:sell-modal/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:payment-modal/>
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
                console.log('Sms sent');
                $("#sms_message").val("");
            });
        });
    </script>
@endpush
