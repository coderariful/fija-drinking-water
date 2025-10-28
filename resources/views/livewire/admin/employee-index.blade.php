<div x-data="">
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h6 class="card-title">{{$title??''}}</h6>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <form method="get">
                                <div class="d-flex justify-content-end">
                                    <input type="text" class="form-control w-50 py-2" name="keyword" wire:model="keyword" placeholder="Search Employee" aria-label="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="table-responsive style-scroll">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center" width="1%">{{__('S/N')}}</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Phone') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <th class="text-center" width="1%">{{ paginationIndex($users, $loop->iteration) }}</th>
                                    <td>{{ $user->name??''}}</td>
                                    <td>{{ $user->phone??'' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info btn-circle" title="Summery" wire:click.prevent="$emitTo('employee-summery-modal', 'open_modal', {{$user->id}})" data-toggle="modal" data-target="#employeeSummeryModal">
                                            <i class="material-icons">assignment</i>
                                        </button>

                                        <a href="{{route('admin.employee.edit', $user->id)}}" class="btn btn-info btn-sm btn-circle" title="Edit"
                                           onclick="return confirm('Are you sure, would you like to edit the user?');">
                                            <i class="material-icons">edit</i>
                                        </a>

                                        <button class="btn btn-danger btn-sm btn-circle" title="Delete" form="delete-{{$user->id}}"
                                                onclick="return confirm('Are you sure, would you like to delete the user?');">
                                            <i class="material-icons">delete</i>
                                        </button>
                                        <form action="{{route('admin.employee.destroy',$user->id)}}" method="POST" id="delete-{{$user->id}}">@csrf @method('DELETE')</form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer justify-content-end">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="employeeSummeryModal" tabindex="-1" aria-labelledby="employeeSummeryModalLabel" aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeSummeryModalLabel">Summery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:employee-summery-modal/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
