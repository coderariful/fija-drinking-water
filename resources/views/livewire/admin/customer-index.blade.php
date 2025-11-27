<div class="row" x-data="{
    exportPhoneNumber() {
       if(confirm('Are you sure you want to export all customer phone numbers?')){
            window.open(`{{ route('export.customer-phone-numbers') }}`, '_blank');
       }
    }
}">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
                <div class="d-flex align-items-center flex-wrap">
                    <h6 class="card-title">{{$title}}</h6>
                    <div class="ml-auto">
                        <button type="button" class="btn btn-danger" onclick="return confirm('{{trans('Are you sure? You want to send SMS to all!')}}') || event.stopImmediatePropagation()" wire:click.prevent="sendToAll">{{trans('Send SMS to All')}}</button>
                        <button type="button" class="btn btn-default" x-on:click="exportPhoneNumber"> Export Phone Number </button>
                    </div>
                </div>
            </div>
            <div class="card-body position-relative">
                <div class="px-3 pb-3">
                    <form action="{{route('print.customer-list')}}" @submit.prevent="const urlParams=new URLSearchParams(new FormData($el)).toString();window.open(`${$el.action}?${urlParams}`,'_blank')">
                        @if(!$employee_id)
                        <input type="hidden" name="view" value="on">
                        @endif
                        <input type="hidden" name="showDue" value="{{$showDue ? 'true' : 'false'}}">
                        <div class="d-flex justify-content-end flex-wrap flex-md-nowrap">
                            @if($showDue)
                                <button type="button" class="btn btn-primary" wire:click="filterDue(false)">{{trans('Show All')}}</button>
                            @else
                                <button type="button" class="btn btn-primary" wire:click="filterDue(true)">{{trans('Show Due')}}</button>
                            @endif
                            <select class="form-control w-25 mr-2" wire:model.live="employee_id" name="employee_id">
                                <option value="">All</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                            <div class="input-group row">
                                <input type="date" class="form-control w-25" wire:model.live="start_date" name="start_date">
                                <input type="date" class="form-control w-25" wire:model.live="end_date" name="end_date">
                                <input type="text" class="form-control w-25" placeholder="Search Customer by Name or Phone" wire:model.live.debounce="keyword" name="keyword">
                                <button class="ml-1 btn btn-success">Print</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="position-absolute w-100 h-100 pt-5" wire:loading
                     style="z-index: 99999; top: 0; left: 0; background: rgba(0, 0, 0, .5); backdrop-filter: blur(2px)">
                    <div class="py-4 text-center bg-light-blue my-5">
                        <h3 class="text-white mb-0">Processing</h3>
                    </div>
                </div>
                <div class="table-responsive style-scroll">
                    <table class="table table-striped table-bordered table-compact">
                        <thead>
                        <tr>
                            <th class="align-middle text-center" rowspan="2" scope="col" nowrap>{{__('S/N')}}</th>
                            <th class="align-middle" rowspan="2" scope="col"></th>
                            <th class="align-middle" rowspan="2" scope="col">{{ __('Employee') }}</th>
                            <th class="align-middle" rowspan="2" scope="col">{{ __('Customer') }}</th>
                            <th class="align-middle text-center py-0" colspan="2" scope="col">{{ __('Jar') }}</th>
                            <th class="align-middle text-center" rowspan="2" scope="col">{{ __('Due') }}</th>
                            <th class="align-middle text-center" rowspan="2" scope="col">{{ __('Issue Date') }} <br> {{ __('Billing Type') }}</th>
                            <th class="align-middle text-center" rowspan="2" scope="col">{{ __('Status') }}</th>
                            <th class="align-middle" rowspan="2" scope="col">{{ __('Action') }}</th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-center align-middle py-0">{{ __('Rate') }}</th>
                            <th scope="col" class="text-center align-middle py-0">{{ __('Stock') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <th class="text-center">{{ paginationIndex($customers, $loop->iteration) }}</th>
                                <td nowrap>
                                    <button type="button"  class="btn btn-sm btn-warning" title="Add Sell" data-toggle="modal" data-target="#sellModal" wire:click="$dispatchTo('sell-modal', 'open-modal', { customer: {{$customer->id}} })" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">shopping_basket</i>
                                    </button>
                                    {{--
                                    <button type="button"  class="btn btn-sm btn-warning" title="Payment" data-toggle="modal" data-target="#paymentModal" wire:click="$dispatchTo('payment-modal', 'open-modal', { customer: {{$customer->id}} })" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">account_balance_wallet</i>
                                    </button>
                                    --}}
                                    <button type="button" class="btn btn-sm btn-info" title="Purchase History" data-toggle="modal" data-target="#historyModal" wire:click="$dispatchTo('purchase-history-modal', 'open-modal', { customer: {{$customer->id}} })" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">assignment</i>
                                    </button>
                                </td>
                                <td>{{ $customer->user?->name??'-'}}</td>
                                <td class="py-4px">
                                    <strong>{{trans('Name')}}:</strong> {{ $customer->name??''}} <br>
                                    <strong>{{trans('Phone')}}:</strong> {{ $customer->phone??'' }} <br>
                                    <strong>{{trans('Address')}}:</strong> <span title="{{$customer->address}}">{{ str($customer->address??'')->limit(20) }}</span>
                                </td>
                                <td class="text-center">{{ $customer->jar_rate??'-' }}</td>
                                <td class="text-center">{{ $customer->jar_stock }}</td>
                                <td class="text-center">{{ $customer->due_amount }}</td>
                                <td class="text-center">{{ formatDate($customer->issue_date, DATE_FORMAT) }} <br> {{ str($customer->billing_type??'')->upper() }}</td>
                                <td class="text-center py-0">
                                    @if($customer->status == CUSTOMER_PENDING)
                                        <select class="bg-warning px-2 py-1 text-center rounded border-0 w-100" @change="$wire.update_status({{$customer->id}}, $el.value)">
                                            <option class="text-center text-white bg-warning" value="{{CUSTOMER_PENDING}}">PENDING</option>
                                            <option class="text-center text-white bg-success" value="{{CUSTOMER_APPROVED}}">APPROVE</option>
                                            <option class="text-center text-white bg-danger" value="{{CUSTOMER_REJECTED}}">REJECT</option>
                                        </select>
                                    @elseif($customer->status == CUSTOMER_APPROVED)
                                        <div class="bg-success px-2 py-1 text-center rounded">APPROVED</div>
                                    @elseif($customer->status == CUSTOMER_REJECTED)
                                        <div class="bg-danger px-2 py-1 text-center rounded">REJECTED</div>
                                    @endif
                                </td>
                                <td nowrap class="py-2px">
                                    <button type="button" class="btn btn-sm btn-info" title="Send SMS" data-toggle="modal" data-target="#smsModal" wire:click="$dispatchTo('admin.sms-modal', 'open-modal', { customer: {{$customer->id}} })" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">send</i>
                                    </button>
                                    <a href="{{route('admin.customer.edit',$customer->id)}}" class="btn btn-sm btn-success" title="Edit" data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-danger" form="delete-{{$customer->id}}" title="Delete"
                                            onclick="return confirm('Are you sure, would you like to delete tha customer?\nIt will delete all sale data for the particular customer.');"
                                            data-bs-toggle="tooltip" data-placement="top">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <form action="{{route('admin.customer.destroy',$customer->id)}}" method="POST" id="delete-{{$customer->id}}"> @csrf @method('DELETE') </form>
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

@script
<script>
    $(function() {
        $(document).on('sms-sent', function() {
            $("#sms_message").val("");
        });
    });
</script>
@endscript
