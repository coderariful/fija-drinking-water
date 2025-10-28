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
                                <input type="text" class="form-control py-2" name="keyword" placeholder="Search product by name, type or sku" wire:model.debounce="keyword" required>
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
                                <th scope="col">{{ __('Template Name') }}</th>
                                <th scope="col">{{ __('Template Body') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($templates as $template)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td>{{ $template->name}}</td>
                                    <td>{{ str($template->body)->limit() }}</td>
                                    <td>
                                        <a href="{{route('admin.sms-template.edit', $template->id)}}" class="btn btn-sm btn-success btn-circle" title="Edit">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer justify-content-end">
                    {{ $templates->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>
</div>
