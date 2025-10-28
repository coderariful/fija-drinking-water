<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListOfInactiveCustomer extends Component
{
    use WithPagination;

    public $title = 'List Of Inactive Customer';
    public $status;
    public $keyword;
    public $start_date;
    public $end_date;
    public $employee_id;

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public function render(): View
    {
        $layout = auth()->user()->user_type==0 ? 'admin.layouts.master' : 'user.layouts.master';
        return view('livewire.list-of-inactive-customer',[
            'customers' => ($this->getCustomers()),
            'employees' => $this->getEmployees(),
        ])->layout($layout, [
            'title' =>$this->title
        ]);
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        return Customer::query()
            ->when($this->keyword, function (Builder $builder, $keyword) {
                $builder->where(function ($query) use ($keyword) {
                    $query->where('name', 'like', "%$keyword%")
                        ->orWhere('phone', 'like', "%$keyword%");
                });
            })
            ->when($this->start_date, function (Builder $builder, $start_date) {
                $builder->whereDate('issue_date', '>=', $start_date);
            })
            ->when($this->end_date, function (Builder $builder, $end_date) {
                $builder->whereDate('issue_date', '<=', $end_date);
            })
            ->when($this->employee_id, function (Builder $builder, $employee_id) {
                $builder->where('user_id', '=', $employee_id);
            })
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
            })
            ->whereDoesntHave('purchase', function (Builder $builder) {
                $builder->whereDate('created_at', '>', today()->subDays(7));
            })
            ->orderBy('status')
            ->with([
                'user',
                'sales' => fn ($q) => $q->latest('created_at')->take(1),
                'payments' => fn ($q) => $q->latest('created_at')->take(1),
            ])
            ->latest('id')
            ->paginate(RECORDS_PER_PAGE);
    }

    public function getEmployees()
    {
        return User::all();
    }
}
