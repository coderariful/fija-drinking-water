<div>
    <div class="row">
        <div class="col">
            <nav class="breadcrumb justify-content-sm-start justify-content-center text-center text-light bg-dark ">
                <a class="breadcrumb-item text-white"
                   href="{{ route('admin.dashboard') }}">{{__('Home')}}</a>
                <span class="breadcrumb-item active">{{$title}}</span>
                <span class="breadcrumb-info" id="time"></span>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <h6 class="card-title">{{$title}}</h6>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="input-group">
                                <input type="text" class="form-control py-2" name="keyword" placeholder="Search product by name, type or sku"
                                       wire:model.live.debounce="keyword" required>
                                <button class="d-md-none button button-purple px-3"><i class="fa fa-search"></i></button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive style-scroll">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">{{__('S/N')}}</th>
                                <th scope="col">{{ __('Product Name') }}</th>
                                <th scope="col">{{ __('Product Type') }}</th>
                                <th scope="col">{{ __('SKU') }}</th>
                                <th scope="col">{{ __('Price/Rate') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <th>{{ paginationIndex($products, $loop->iteration) }}</th>
                                    <td>{{ $product->name}}</td>
                                    <td>{{ $product->types[$product->type] ?? str($product->type)->title }}</td>
                                    <td>{{ $product->sku??'-' }}</td>
                                    <td>{{ $product->price }}</td>

                                    <td>
                                        <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-sm btn-success btn-circle" title="Edit">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        @if($product->id !== 1)
                                        <button type="button" class="btn btn-sm btn-danger btn-circle" title="Delete"
                                                onclick="return confirm('Are you sure, would you like to delete tha user?') || event.stopImmediatePropagation();"
                                                wire:click.prevent="delete({{$product->id}})">
                                            <i class="material-icons">delete</i>
                                        </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger btn-circle disabled" title="Delete">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer justify-content-end">
                    {{ $products->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>
</div>
