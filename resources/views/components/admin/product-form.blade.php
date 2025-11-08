<div class="row">
    <div class="col-sm-10 col-md-8 col-lg-6">
        <div class="form-group">
            <label for="employee" class="font-weight-bold text-uppercase">Product Type:</label>
            <select class="form-control" id="employee" name="type" wire:model="type">
                <option value="">Select Product Type</option>
                @foreach($productTypes as $key => $value)
                    <option value="{{ $key }}">{{$value}}</option>
                @endforeach
            </select>
            @if ($errors->has('type'))
                <span class="text-danger">{{ $errors->first('type') }}</span>
            @endif
        </div>


        <div class="form-group">
            <label for="type" class="card-title font-weight-bold">{{__('Name:')}}</label>
            <input type="text" id="name" class="form-control" placeholder="{{__('Name')}}" wire:model="name">
            @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
        </div>


        <div class="form-group">
            <label for="sku" class="card-title font-weight-bold">{{__('SKU:')}}</label>
            <input type="text" name="sku" id="sku" class="form-control" placeholder="{{__('SKU')}}" wire:model="sku">
            @if ($errors->has('sku'))
                <span class="text-danger">{{ $errors->first('sku') }}</span>
            @endif
        </div>


        <div class="form-group">
            <label for="price" class="card-title font-weight-bold">{{__('Price/Rate:')}}</label>
            <input type="number" name="price" id="price" step="any" class="form-control" placeholder="{{__(' Price/Rate')}}" wire:model="price">
            @if ($errors->has('price'))
                <span class="text-danger">{{ $errors->first('price') }}</span>
            @endif
        </div>
    </div>
</div>
