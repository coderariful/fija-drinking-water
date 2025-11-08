<div class="row" x-data="{}">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
                <div class="row">
                    <div class="col-md-6 col-sm-12 d-flex justify-content-between align-items-center">
                        <h6 class="card-title">{{$title}}</h6>
                        <select class="form-control w-25" wire:model.live="employee_id">
                            <option value="">All</option>
                            @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                            @endforeach
                        </select>
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
                            <th scope="col">{{__('Serial')}}</th>
                            <th scope="col">{{ __('Employee') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Address') }}</th>
                            <th scope="col" class="text-center">{{ __('Jar Rate') }}</th>
                            <th scope="col">{{ __('Issue Date') }}</th>
                            <th scope="col">{{ __('Billing Type') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <th>{{ paginationIndex($customers, $loop->iteration) }}</th>
                                <td>
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{ $customer->user?->name??'-' }} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{$customer->original?->user?->name??'-' }}</td>
                                <td>
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{ $customer->name??'' }} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{$customer->original?->name??'' }}</td>
                                <td>
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{ $customer->phone??'' }} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{$customer->original?->phone??'' }}</td>
                                <td>
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{$customer->address??'-'}} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{$customer->original?->address??'-' }}</td>
                                <td class="text-center">
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{ $customer->jar_rate??'-' }} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{$customer->original?->jar_rate??'-' }}</td>
                                <td>
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{ $customer->issue_date??'' }} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{$customer->original?->issue_date??'-' }}</td>
                                <td>
                                    <span class="text-light-blue">{{trans('NEW:')}}</span>
                                    {{ str($customer->billing_type??'')->upper }} <br>
                                    <span class="text-light-green">{{trans('OLD:')}}</span>
                                    {{str($customer->original?->billing_type??'')->upper}}</td>
                                <td>
                                    @if($customer->status == CUSTOMER_PENDING)
                                        <select class="bg-warning px-2 py-1 text-center rounded border-0 w-100" @change="$wire.update_status({{$customer->id}}, $el.value)">
                                            <option class="text-center text-white bg-warning" value="{{CUSTOMER_PENDING}}">{{trans('PENDING')}}</option>
                                            <option class="text-center text-white bg-success" value="{{CUSTOMER_ACCEPTED}}">{{trans('ACCEPT')}}</option>
                                            <option class="text-center text-white bg-danger" value="{{CUSTOMER_REJECTED}}">{{trans('REJECT')}}</option>
                                        </select>
                                    @elseif($customer->status == CUSTOMER_ACCEPTED)
                                        <div class="bg-success px-2 py-1 text-center rounded">{{trans('ACCEPTED')}}</div>
                                    @elseif($customer->status == CUSTOMER_REJECTED)
                                        <div class="bg-danger px-2 py-1 text-center rounded">{{trans('REJECTED')}}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 text-center" colspan="11">{{trans('No customer found')}}</td>
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
