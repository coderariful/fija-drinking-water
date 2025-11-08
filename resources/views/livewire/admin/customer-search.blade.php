<div x-data="{}">
    <div class="row justify-content-center">
        <div class="col-md-6 d-flex gap-5">
            <input type="text" class="form-control" placeholder="Search customer" wire:model.live="keyword">
            <a href="{{route('admin.customer.create')}}" class="btn btn-primary ml-3">Add Customer</a>
        </div>
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-body">
                    <div class="table-responsive style-scroll">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">{{__('Serial')}}</th>
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
                                    <td>{{ $customer->user?->name??'-'}}</td>
                                    <td>{{ $customer->name??''}}</td>
                                    <td>{{ $customer->phone??'' }}</td>
                                    <td>
                                        <span title="{{$customer->address}}">{{ str($customer->address??'')->limit(20) }}</span>
                                    </td>
                                    <td class="text-center">{{ $customer->jar_rate??'-' }}</td>
                                    <td class="text-center">{{ $customer->jar_stock }}</td>
                                    <td class="text-center">{{ $customer->due_amount }}</td>
                                    <td>{{ $customer->issue_date??'' }}</td>
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
                                    <td nowrap>
                                        <button type="button"  class="btn btn-sm btn-warning btn-circle" title="Add Sell" data-toggle="modal" data-target="#sellModal" wire:click="$dispatchTo('sell-modal', 'open-modal', {{$customer->id}})">
                                            <i class="material-icons">shopping_basket</i>
                                        </button>
                                        <button type="button"  class="btn btn-sm btn-warning btn-circle" title="Payment" data-toggle="modal" data-target="#paymentModal" wire:click="$dispatchTo('payment-modal', 'open-modal', {{$customer->id}})">
                                            <i class="material-icons">account_balance_wallet</i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info btn-circle" title="Purchase History" data-toggle="modal" data-target="#historyModal" wire:click="$dispatchTo('purchase-history-modal', 'open-modal', {{$customer->id}})">
                                            <i class="material-icons">assignment</i>
                                        </button>
                                        @if(auth()->user()->user_type==0)
                                        <a href="{{route('admin.customer.edit',$customer->id)}}" class="btn btn-sm btn-success btn-circle" title="Edit">
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
                        <div class="mt-3">
                            {{$customers->links('pagination::bootstrap-4')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('includes.customer-modals')
</div>
