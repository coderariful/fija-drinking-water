<div class="row" x-data="{}">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
                <form action="{{ $printUrl }}" onsubmit="return confirm('Are you sure to print?')" target="_blank">
                    <input type="hidden" name="view" value="on">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 d-flex justify-content-between align-items-center">
                            <h6 class="card-title">{{$title}}</h6>
                            {{-- <button type="button" class="btn btn-danger" onclick="return confirm('{{trans('Are you sure?')}}') || event.stopImmediatePropagation()" wire:click="sendToAll">{{trans('Send SMS to All')}}</button> --}}
                            @if(auth_user()->user_type==0)
                            <select class="form-control w-25" wire:model.live="employee_id" name="employee_id">
                                <option value="">All</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        <div class="col-md-6 col-sm-12 d-flex">
                            <div class="input-group">
                                {{--<input type="date" class="form-control w-25" wire:model.live="start_date" name="start_date">--}}
                                {{--<input type="date" class="form-control w-25" wire:model.live="end_date" name="end_date">--}}
                                <input type="text" class="form-control w-25" placeholder="Search Customer by Name or Phone" wire:model.live.debounce="keyword" name="keyword">
                            </div>
                            <button class="ml-2 btn btn-success">Print</button>
                        </div>
                    </div>
                 </form>
            </div>
            <div class="card-body position-relative">
                <x-admin.table-processing-indicator/>
                <div class="table-responsive style-scroll">
                    <table class="table table-striped table-bordered table-compact">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center" nowrap>{{__('S/N')}}</th>
                            <th scope="col">{{ __('Employee') }}</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Address') }}</th>
                            <th scope="col" class="text-center">{{ __('Jar Rate') }}</th>
                            <th scope="col" class="text-center">{{ __('Stock') }}</th>
                            <th scope="col" class="text-center">{{ __('Due') }}</th>
                            <th scope="col" class="text-center">{{ __('Last Transaction') }}</th>
                            <th scope="col">{{ __('Issue Date') }}</th>
                            <th scope="col">{{ __('Billing Type') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <th class="text-center">{{ paginationIndex($customers, $loop->iteration) }}</th>
                                <td>{{ $customer->user?->name??'-'}}</td>
                                <td>
                                    <div class="d-flex align-content-center" style="gap: 3px">
                                        <span class="flex-shrink-0" title="Status: {{ $customer->status_label }}">
                                            @if($customer->status == CUSTOMER_PENDING)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="icon text-warning">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -15 8.66l.005 -.324a10 10 0 0 1 14.995 -8.336m-5 11.66a1 1 0 0 0 -1 1v.01a1 1 0 0 0 2 0v-.01a1 1 0 0 0 -1 -1m0 -7a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-4a1 1 0 0 0 -1 -1" />
                                                </svg>
                                            @elseif($customer->status == CUSTOMER_APPROVED)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="icon text-success">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" />
                                                </svg>
                                            @elseif($customer->status == CUSTOMER_REJECTED)
                                                <span class="text-danger text-center"></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" class="icon text-danger">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-6.489 5.8a1 1 0 0 0 -1.218 1.567l1.292 1.293l-1.292 1.293l-.083 .094a1 1 0 0 0 1.497 1.32l1.293 -1.292l1.293 1.292l.094 .083a1 1 0 0 0 1.32 -1.497l-1.292 -1.293l1.292 -1.293l.083 -.094a1 1 0 0 0 -1.497 -1.32l-1.293 1.292l-1.293 -1.292l-.094 -.083z" />
                                                </svg>
                                            @endif
                                        </span>
                                        <span>{{ $customer->name??''}}</span>
                                    </div>
                                </td>
                                <td>{{ $customer->phone??'' }}</td>
                                <td>
                                    <span title="{{$customer->address}}">{{ str($customer->address??'')->limit(20) }}</span>
                                </td>
                                <td class="text-center">{{ $customer->jar_rate ? round($customer->jar_rate, 2) : '-' }}</td>
                                <td class="text-center">{{ $customer->jar_stock }}</td>
                                <td class="text-right">{{ $customer->due_amount }}</td>
                                <td class="text-center" style="padding-block: .15rem; line-height: 1">
                                    {{ $customer->last_transaction_date?->format('d-M-y') ?? '-' }}
                                    <br>
                                    <small>{{ $customer->last_transaction_date?->diffForHumans() }}</small>
                                </td>
                                <td class="nowrap" nowrap>{{ formatDate($customer->issue_date, 'd-M-y')??'' }}</td>
                                <td>{{ str($customer->billing_type??'')->upper() }}</td>
                                <td nowrap class="py-0">
                                    <button type="button" class="btn btn-sm btn-info" title="Purchase History"
                                            data-toggle="modal" data-target="#historyModal"
                                            wire:click="$dispatchTo('purchase-history-modal', 'open-modal', { customer: {{$customer->id}} })">
                                        <i class="material-icons">assignment</i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info" title="Send SMS"
                                            data-toggle="modal" data-target="#smsModal"
                                            wire:click="$dispatchTo('admin.sms-modal', 'open-modal', { customer: {{$customer->id}} })">
                                        <i class="material-icons">send</i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 text-center" colspan="14">No customer found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
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
