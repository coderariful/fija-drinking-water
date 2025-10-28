<div class="row">
    <div class="col-md-6">
        <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Name:')}}</label></p>
        <div class="input-group input-group-lg mb-3">
            <input type="text" name="name" id="name" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                   placeholder="{{__('Input here')}}" value="{{$employee->name??''}}">
            <br>
            @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
        </div>

        <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Phone:')}}</label></p>
        <div class="input-group input-group-lg mb-3">
            <input type="text" name="phone" id="phone" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                   placeholder="{{__('Phone number')}}" value="{{$employee->phone??''}}">
            <br>
            @if ($errors->has('phone'))
                <span class="text-danger">{{ $errors->first('phone') }}</span>
            @endif
        </div>
    </div>
    <div class="col-md-6">
        <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Password:')}}</label></p>
        <div class="input-group input-group-lg mb-3">
            <input type="password" name="password" id="password" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                   placeholder="{{__('Password')}}" value="">
            <br>
            @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
        </div>
        <p class="mb-1"><label for="name" class="card-title font-weight-bold">{{__('Confirm Password:')}}</label></p>
        <div class="input-group input-group-lg mb-3">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm"
                   placeholder="{{__('Confirm Password')}}" value="">
            <br>
            @if ($errors->has('password_confirmation'))
                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>
    </div>
</div>
