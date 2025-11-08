<div>
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('user.dashboard') }}">{{__('Home')}}</a>
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.customer.index') }}">{{__('All Customer')}}</a>
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
                        <x-admin.product-form/>

                        <div class="card-footer">
                            <button class="btn btn-wave-light btn-danger btn-lg" type="submit">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
