<div class="row">
    <div class="col-md-10 col-lg-8">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="billing_type" class="font-weight-bold text-uppercase">Billing Type:</label>
                    <select class="form-control" id="billing_type" name="billing_type">
                        <option value="">Select Billing Type</option>
                        <option value="{{BILLING_DAILY}}" @selected(old('billing_type', @$customer?->billing_type ?? '') == BILLING_DAILY)>Daily</option>
                        <option value="{{BILLING_MONTHLY}}" @selected(old('billing_type', @$customer?->billing_type ?? '') == BILLING_MONTHLY)>Monthly</option>
                    </select>
                    @error('billing_type')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="card-title font-weight-bold">{{__('Name:')}}</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="{{__('Name')}}"   value="{{old('name', @$customer->name??'')}}">
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone" class="card-title font-weight-bold">{{__('Phone:')}}</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="{{__('Phone number')}}" value="{{old('phone', @$customer?->phone??'')}}">
                    @if ($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="address" class="card-title font-weight-bold">{{__('Address:')}}</label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="{{__('Address')}}" value="{{old('address', @$customer?->address??'')}}">
                    @if ($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="issue_date" class="card-title font-weight-bold">{{__('Issue Date:')}}</label>
                    <input type="date" name="issue_date" id="issue_date" class="form-control" placeholder="{{__('Issue Date')}}" value="{{old('issue_date', formatDate(@$customer?->issue_date??null, 'Y-m-d'))}}">
                    @if ($errors->has('issue_date'))
                        <span class="text-danger">{{ $errors->first('issue_date') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="jar_rate" class="card-title font-weight-bold">{{__('Jar Rate:')}}</label>
                    <input type="number" name="jar_rate" id="jar_rate" step="any" class="form-control" placeholder="{{__(' Jar Rate')}}" value="{{old('jar_rate', @$customer?->jar_rate??'')}}">
                    @if ($errors->has('jar_rate'))
                        <span class="text-danger">{{ $errors->first('jar_rate') }}</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
