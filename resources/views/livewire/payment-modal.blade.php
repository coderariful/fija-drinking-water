<div x-data="{}">
    <div class="mb-3">
        @if($customer)
            <div class="border pt-3">
                <table class="table text-center mb-1">
                    <tr>
                        <th class="p-0 text-uppercase">Customer Name</th>
                        <th class="p-0" colspan="2">
                            <h6>Due Amount</h6>
                        </th>
                        <th class="p-0 text-uppercase">Customer Phone</th>
                    </tr>
                    <tr>
                        <td class="p-0">{{$customer->name}}</td>
                        <td class="p-0" colspan="2">
                            <h4>{{$customer->due_amount}}</h4>
                        </td>
                        <td class="p-0">{{$customer->phone}}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>


    <div class="row">
        <div class="form-group{{auth_user()->isAdmin()?' col-6':' col-12'}}">
            <label for="pay_amount">Pay Amount</label>
            <input id="pay_amount" type="number" step="any" class="form-control form-control-lg bg-white text-dark"
                   wire:model.defer="pay_amount" min="0"/>
            @error('pay_amount')
            <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        @if(auth_user()->isAdmin())
            <div class="form-group col-6">
                <label for="date">Date</label>
                <input id="date" type="date" class="form-control form-control-lg bg-white text-dark"
                       wire:model.defer="date" min="0"/>
                @error('date')
                <span class="text-danger">{{$message}}</span>
                @enderror
            </div>
        @endif
    </div>
    <div class="form-group">
        <label for="note">Note</label>
        <input id="note" type="text" class="form-control" wire:model.defer="note" placeholder="Write a note"/>
        @error('note')
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>

    <button type="submit" class="btn btn-success text-uppercase" wire:click="submit">Submit</button>
</div>
