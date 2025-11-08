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
                            <select class="form-control" wire:model.blur="employee_id">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" wire:model.blur="customer_id">
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" wire:model.blur="product_id">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" x-data="{type: 'day'}">
                        <div class="col-md-3">
                            <select class="form-control" x-model="type">
                                <option value="day">Single Day</option>
                                <option value="range">Date Range</option>
                            </select>
                        </div>
                        <div class="col-md-3" x-show="type=='day'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark">Date</span>
                                <input type="date" class="form-control" wire:model.live="only_date">
                            </div>
                        </div>
                        <div class="col-md-3" x-show="type=='range'">
                            <div class="input-group">
                                <span class="input-group-text bg-white text-dark">From</span>
                                <input type="date" class="form-control" wire:model.live="start_date">
                            </div>
                        </div>
                        <div class="col-md-3" x-show="type=='range'">
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
                            <th scope="col">{{ __('Date') }}</th>
                            <th scope="col">{{ __('Employee') }}</th>
                            <th scope="col">{{ __('Customer') }}</th>
                            <th scope="col">{{ __('Phone') }}</th>
                            <th scope="col" class="text-center">{{ __('Pay Amount') }}</th>
                            <th scope="col">{{ __('Note') }}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <th class="py-0 text-center">{{ paginationIndex($sales, $loop->iteration) }}</th>
                                <td class="py-1" x-data="{edit: false}">

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
                                <td class="py-0">{{ $sale->user?->name??'-'}}</td>
                                <td class="py-0">{{ $sale->customer?->name??'-'}}</td>
                                <td class="py-0">{{ $sale->customer?->phone??'-' }}</td>
                                <td class="py-0 text-center" x-data="{edit: false}">
                                    <span x-show="!edit">
                                        {{ $sale->amount }}
                                        <a href="javascript:void(0)" @click="edit=true" >
                                            <i class="fa fa-pencil text-secondary ml-2" wire:loading.attr="hidden" wire:target="saveAmountUpdate({{$sale->id}})"></i>
                                            <span class="fa fa-spinner fa-spin" wire:loading wire:target="saveAmountUpdate({{$sale->id}})"></span>
                                        </a>
                                    </span>

                                    <span class="form-group my-1 justify-content-center" x-bind:class="{'d-flex': edit}" x-show="edit" x-on:entryUpdated="edit=false">
                                        <input type="number" min="1" class="border-0 form-control-sm text-center" style="width:70px;font-size:14px;" value="{{$sale->amount}}" wire:model="amounts.{{$sale->id}}">
                                        <button wire:click="saveAmountUpdate({{$sale->id}})" x-on:click.debounce="edit=false" class="border-0 text-white bg-success">
                                            <i class="fa fa-check" wire:loading.attr="hidden" wire:target="saveAmountUpdate({{$sale->id}})"></i>
                                            <span class="fa fa-spinner fa-spin" wire:loading wire:target="saveAmountUpdate({{$sale->id}})"></span>
                                        </button>
                                        <button x-on:click="edit=false" class="border-0 text-white bg-danger"><i class="fa fa-times"></i></button>
                                    </span>
                                </td>
                                <td class="py-0">{{ str($sale->note??'-')->limit() }}</td>
                                 <td nowrap>
                                     <button type="button" class="btn btn-danger btn-sm btn-circle" onclick="return confirm('{{trans('Are you sure?')}}') || event.stopImmediatePropagation()" wire:click.prevent="deletePayment({{$sale->id}})">
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
