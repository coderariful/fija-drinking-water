<div x-data="">
    <div class="row">
        <div class="col-12">
            <div class="card card-dark bg-dark">
                <div class="card-header d-block">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h6 class="card-title">{{$title ?? ''}}</h6>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <form method="get">
                                <div class="d-flex justify-content-end">
                                    <input type="text" class="form-control w-50 py-2" name="keyword"
                                        wire:model.live="keyword" placeholder="Search Employee" aria-label="Search">
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
                                    <th scope="col" class="text-center" width="1%" nowrap>{{__('S/N')}}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col">{{ __('Total Stock') }}</th>
                                    <th scope="col">{{ __('Total Due') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_stock = 0;
                                    $total_due = 0;
                                @endphp
                                @foreach($users as $user)
                                    @php
                                        $total_stock += $user->jar_stock ?? 0;
                                        $total_due += $user->due_amount ?? 0;
                                    @endphp
                                    <tr>
                                        <th class="text-center" width="1%">{{ paginationIndex($users, $loop->iteration) }}
                                        </th>
                                        <td>{{ $user->name ?? ''}}</td>
                                        <td>{{ $user->phone ?? '' }}</td>
                                        <td>{{ round($user->jar_stock ?? 0, 2) }}</td>
                                        <td>{{ round($user->due_amount ?? 0, 2) }}</td>
                                        <td class="py-1">
                                            <button type="button" class="btn btn-sm btn-info" title="Summery"
                                                wire:click.prevent="$dispatchTo('employee-summery-modal', 'open_modal', { user: {{$user->id}} })"
                                                data-toggle="modal" data-target="#employeeSummeryModal">
                                                <i class="material-icons">assignment</i>
                                            </button>

                                            @if($user->id != auth()->id())
                                                <button type="button" class="btn btn-sm btn-info" title="Login As"
                                                    wire:click.prevent="$call('impersonate', { user: {{$user->id}} })"
                                                    data-toggle="tooltip" data-placement="top">
                                                    <i class="material-icons">assignment_ind</i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-info" disabled>
                                                    <i class="material-icons">assignment_ind</i>
                                                </button>
                                            @endif

                                            @if($user->user_type != USER_ADMIN)
                                                <a href="{{route('admin.employee.edit', $user->id)}}"
                                                    class="btn btn-info btn-sm" title="Edit"
                                                    onclick="return confirm('Are you sure, would you like to edit the user?');">
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <button class="btn btn-danger btn-sm" title="Delete" form="delete-{{$user->id}}"
                                                    onclick="return confirm('Are you sure, would you like to delete the user?\nThis will also delete all related customers and sales data!');">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @else
                                                <a href="#" class="btn btn-info btn-sm disabled" title="Edit" disabled>
                                                    <i class="material-icons">edit</i>
                                                </a>
                                                <button class="btn btn-danger btn-sm" title="Delete" disabled>
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endif
                                            <form action="{{route('admin.employee.destroy', $user->id)}}" method="POST"
                                                id="delete-{{$user->id}}">@csrf @method('DELETE')</form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-right">Total</th>
                                    <th>{{ round($total_stock ?? 0, 2) }}</th>
                                    <th>{{ round($total_due ?? 0, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        {{-- @dump($jar_stock) --}}
                    </div>
                </div>
                <div class="card-footer justify-content-end">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="employeeSummeryModal" tabindex="-1" aria-labelledby="employeeSummeryModalLabel"
        aria-hidden="true" wire:ignore>
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeSummeryModalLabel">Summery</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:employee-summery-modal />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
