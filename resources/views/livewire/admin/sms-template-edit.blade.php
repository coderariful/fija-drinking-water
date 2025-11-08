<div>
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('user.dashboard') }}">{{__('Home')}}</a>
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.sms-template') }}">{{__('SMS Templates')}}</a>
                <span class="breadcrumb-item active">{{$title}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header">
                    <h6 class="card-title">{{$title}}</h6>
                </div>
                <form method="POST" wire:submit="submit">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label for="name" class="card-title font-weight-bold">{{__('Template Name:')}}</label>
                                    <input type="text" id="name" class="form-control" placeholder="{{__('Template Name')}}" wire:model="name">
                                    @error ('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="my-4">
                                    <strong>Parameters:</strong>
                                    @foreach($template->params as $key => $value)
                                        <p class="mb-0">
                                            <code>{{$key}}</code> : {{$value}}
                                        </p>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <label for="body" class="card-title font-weight-bold">{{__('Template Body:')}}</label>
                                    <textarea name="body" id="body" class="form-control" placeholder="{{__('Template Body')}}" wire:model="body"></textarea>
                                    @error ('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
