<div class="row" x-data="{}">
    <div class="col-12">
        <div class="card card-dark bg-dark">
            <div class="card-header d-block">
                <div class="row">
                     <div class="col-md-6 col-sm-12 d-flex justify-content-between align-items-center">
                         <h6 class="card-title">{{$title}}</h6>
                     </div>
                    <div class="col-md-6 col-sm-12">
                        <input type="text" class="form-control border-0" placeholder="Search Customer by Name or Phone" wire:model.live.debounce="keyword">
                    </div>

                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <select class="form-control border-0" wire:model.live="employee_id">
                                <option value="">All Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control border-0" wire:model.live="customer_id">
                                <option value="">All Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control border-0" wire:model.live="product_id">
                                <option value="">All Product</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" x-data="{type: 'day'}">
                        <div class="col-md-3">
                            <select class="form-control border-0" x-model="type" wire:model.live="type">
                                <option value="day">Single Day</option>
                                <option value="range">Date Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" x-show="type=='day'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark border-right">Date</span>
                                <input type="date" class="form-control border-0" wire:model.live="only_date">
                            </div>
                        </div>
                        <div class="col-md-3" x-show="type=='range'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark border-right">From</span>
                                <input type="date" class="form-control border-0" wire:model.live="start_date">
                            </div>
                        </div>
                        <div class="col-md-3" x-show="type=='range'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark border-right">To</span>
                                <input type="date" class="form-control border-0" wire:model.live="end_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive style-scroll">
                    <table class="table table-striped table-bordered table-compact">
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
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <th class="py-1 text-center">{{ paginationIndex($sales, $loop->iteration) }}</th>
                                <td class="py-1" x-data="{edit: false}" nowrap>

                                    <span x-show="!edit">
                                        {{ $sale->created_at?->format('d-M-Y') }}
                                        <a href="javascript:void(0)" @click="edit=true" >
                                            <i class="fa fa-pencil text-secondary ml-2" wire:loading.attr="hidden" wire:target="saveDateUpdate({{$sale->id}})"></i>
                                            <span class="fa fa-spinner fa-spin" wire:loading wire:target="saveDateUpdate({{$sale->id}})"></span>
                                        </a>
                                    </span>

                                    <span class="form-group my-1" x-bind:class="{'d-flex': edit}" x-show="edit" x-on:entryUpdated="edit=false">
                                        <input type="date" class="border-0 form-control-sm" style="width:100px;font-size:14px;" value="{{$sale->created_at?->format('Y-m-d')}}" wire:model="date_created.{{$sale->id}}">
                                        <button wire:click="saveDateUpdate({{$sale->id}})" x-on:click.debounce="edit=false" class="border-0 text-white bg-success">
                                            <i class="fa fa-check" wire:loading.attr="hidden" wire:target="saveDateUpdate({{$sale->id}})"></i>
                                            <span class="fa fa-spinner fa-spin" wire:loading wire:target="saveDateUpdate({{$sale->id}})"></span>
                                        </button>
                                        <button x-on:click="edit=false" class="border-0 text-white bg-danger"><i class="fa fa-times"></i></button>
                                    </span>

                                </td>
                                <td class="py-1">{{ $sale->user?->name??'-'}}</td>
                                <td class="py-1">{{ $sale->customer?->name??'-'}}</td>
                                <td class="py-1">{{ $sale->customer?->phone??'-' }}</td>
                                <td class="py-1">{{ $sale->product?->name??'-' }} {{ $sale->product?->sku ? "({$sale->product->sku})" : "" }}</td>
                                <td class="py-1 text-center">{{ $sale->rate }}</td>
                                <td class="py-1 text-center" x-data="{edit: false}" nowrap>
                                    <span x-show="!edit">{{ $sale->quantity }}
                                        <a href="javascript:void(0)" @click="edit=true" >
                                            <i class="fa fa-pencil text-secondary ml-2" wire:loading.attr="hidden" wire:target="saveQtyUpdate({{$sale->id}})"></i>
                                            <span class="fa fa-spinner fa-spin" wire:loading wire:target="saveQtyUpdate({{$sale->id}})"></span>
                                        </a>
                                    </span>

                                    <span class="form-group my-1 justify-content-center" x-bind:class="{'d-flex': edit}" x-show="edit" x-on:entryUpdated="edit=false">
                                        <input type="number" min="1" class="border-0 form-control-sm text-center" style="width:45px;font-size:14px;" value="{{$sale->quantity}}" wire:model="quantity.{{$sale->id}}">
                                        <button wire:click="saveQtyUpdate({{$sale->id}})" x-on:click.debounce="edit=false" class="border-0 text-white bg-success">
                                            <i class="fa fa-check" wire:loading.attr="hidden" wire:target="saveQtyUpdate({{$sale->id}})"></i>
                                            <span class="fa fa-spinner fa-spin" wire:loading wire:target="saveQtyUpdate({{$sale->id}})"></span>
                                        </button>
                                        <button x-on:click="edit=false" class="border-0 text-white bg-danger"><i class="fa fa-times"></i></button>
                                    </span>
                                </td>
                                <td class="py-1 text-center">{{ round($sale->total_amount, 2) }}</td>
                                <td class="py-1 text-center">{{ round($sale->paid_amount, 2) }}</td>
                                <td class="py-1">{{ str($sale->note??'-')->limit() }}</td>
                                <td class="py-2px">
                                    @php($deleteConfirmMsg = trans('Are you sure? You want to delete this sale?\nThis action cannot be undone.'))
                                    <button type="button" class="btn btn-danger btn-sm py-0" onclick="return confirm('{{$deleteConfirmMsg}}') || event.stopImmediatePropagation()" wire:click.prevent="deleteSale({{$sale->id}})">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
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
