<div x-data="dataSellModal">
    <div class="mb-3">
        @if($customer)
            <div class="border pt-2">
                <table class="table text-center mb-2">
                    <tr>
                        <th class="p-0">Customer Name</th>
                        <th class="p-0">Customer Phone</th>
                        <th class="p-0">Due Amount</th>
                    </tr>
                    <tr>
                        <td class="p-0">{{$customer->name}}</td>
                        <td class="p-0">{{$customer->phone}}</td>
                        <td class="p-0 pt-1">
                            <h5>{{$customer->due_amount}}</h5>
                        </td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
    <div class="form-group">
        <label for="product_id">Product</label>
        <select name="product_id" id="product_id" class="form-control" wire:model.live="product_id">
            @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}} --- Price: {{$product->price}} Tk.</option>
            @endforeach
        </select>
        @error('product_id')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
    <div class="row">
        <div class="form-group col-4">
            <label for="rate">Rate</label>
            <input id="rate" type="number" step="any" class="form-control" wire:model.live="rate" min="0"/>
            @error('rate')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group col-4">
            <label for="quantity">Jar In Qty.</label>
            <input id="quantity" type="number" class="form-control" wire:model.live="quantity" min="0"/>
            @error('quantity')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group col-4">
            <label for="out_quantity">Jar Out Qty.</label>
            <input id="out_quantity" type="number" class="form-control" wire:model.live="out_quantity" min="0"/>
            @error('out_quantity')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group {{auth_user()->isAdmin()?'col-4':'col-6'}}">
            <label for="total_cost">Total Amt.</label>
            <input id="total_cost" type="number" step="any" class="form-control bg-white text-dark"
                   wire:model="total_cost" min="0" readonly/>
            @error('total_cost')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-group {{auth_user()->isAdmin()?'col-4':'col-6'}}">
            <label for="pay_amount">Pay Amount</label>
            <input id="pay_amount" type="number" step="any" class="form-control bg-white text-dark"
                   wire:model="pay_amount" min="0"/>
            @error('pay_amount')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        @if(auth_user()->isAdmin())
            <div class="form-group col-4">
                <label for="date">Date</label>
                <input id="date" type="date" class="form-control bg-white text-dark" wire:model="date" min="0"/>
                @error('date')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
        @endif
    </div>
    <div class="form-group">
        <label for="note">Note</label>
        <input id="note" type="text" class="form-control" wire:model="note" placeholder="Write a note"/>
        @error('note')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>


    <button type="submit" class="btn btn-success text-uppercase" wire:click="submit">Submit</button>
</div>
@push('scripts')
    <script>
        document.addEventListener('alpine:init', function () {
            Alpine.data('dataSellModal', () => ({
                product_id: @entangle('product_id').live,
                rate: @entangle('rate').live,
                quantity: @entangle('quantity').live,
                total_cost: @entangle('total_cost').live,
            }))
        })
    </script>
@endpush
