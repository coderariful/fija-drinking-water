<div class="row" x-data="{}">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
                <div class="row">
                     <div class="col-md-6 col-sm-12 d-flex justify-content-between align-items-center">
                         <h6 class="card-title">{{$title}}</h6>
                     </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="text" class="form-control" placeholder="Search Customer by Name or Phone" wire:model.live.debounce="keyword">
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <select class="form-control" wire:model.blur="customer_id" aria-label="Select Customer">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" wire:model.blur="product_id" aria-label="Select Product">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" x-data="{type: 'day'}">
                        <div class="col-md-3">
                            <select class="form-control" x-model="type" wire:model.live="type">
                                <option value="day">Single Day</option>
                                <option value="range">Date Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" x-show="type==='day'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark">Date</span>
                                <input type="date" class="form-control" wire:model.live="only_date">
                            </div>
                        </div>
                        <div class="col-md-3" x-show="type==='range'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark">From</span>
                                <input type="date" class="form-control" wire:model.live="start_date">
                            </div>
                        </div>
                        <div class="col-md-3" x-show="type==='range'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark">To</span>
                                <input type="date" class="form-control" wire:model.live="end_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive style-scroll">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th scope="col" class="text-center">{{__('SN')}}</th>
                            <th scope="col" nowrap>{{ __('Date') }}</th>
                            <th scope="col">{{ __('Employee') }}</th>
                            <th scope="col">{{ __('Customer') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col">{{ __('Product') }}</th>
                            <th scope="col" class="text-center">{{ __('Rate') }}</th>
                            <th scope="col" class="text-center" nowrap>{{ __('Qty.') }}</th>
                            <th scope="col" class="text-center">{{ __('Total Amount') }}</th>
                            <th scope="col" class="text-center">{{ __('Paid Amount') }}</th>
                            <th scope="col">{{ __('Note') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <th class="py-1 text-center">{{ paginationIndex($sales, $loop->iteration) }}</th>
                                <td class="py-1">{{ $sale->created_at?->format('d-M-Y') }}</td>
                                <td class="py-1">{{ $sale->user?->name??'-'}}</td>
                                <td class="py-1">{{ $sale->customer?->name??'-'}}</td>
                                <td class="py-1">{{ $sale->customer?->phone??'-' }}</td>
                                <td class="py-1">{{ $sale->product?->name??'-' }} {{ $sale->product?->sku ? "({$sale->product->sku})" : "" }}</td>
                                <td class="py-1 text-center">{{ $sale->rate }}</td>
                                <td class="py-1 text-center" nowrap>{{ $sale->quantity }}</td>
                                <td class="py-1 text-center">{{ $sale->total_amount }}</td>
                                <td class="py-1 text-center">{{ $sale->paid_amount }}</td>
                                <td class="py-1">{{ str($sale->note??'-')->limit() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-3 text-center" colspan="11">No sale found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer justify-content-end">
                {{ $sales->links('livewire::bootstrap') }}
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div class="modal fade" id="exModal" tabindex="-1" aria-labelledby="exModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exModalLabel">Send SMS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@endpush
