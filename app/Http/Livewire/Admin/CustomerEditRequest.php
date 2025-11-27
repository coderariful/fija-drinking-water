<?php

namespace App\Http\Livewire\Admin;

use App\Models\Customer;
use App\Models\CustomerHistory;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerEditRequest extends Component
{
    use WithPagination;

    public $title;
    public $keyword;
    public $start_date;
    public $end_date;
    public $employee_id;

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public function update_status(CustomerHistory $customer, $status)
    {
        $columns = ['name', 'phone', 'address', 'issue_date', 'billing_type', 'jar_rate'];

        $customer->original->update($customer->only($columns));

        $customer->update(['status' => $status]);

        flash("Customer edit request " . Customer::STATUS[$status]);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.customer-edit-request', [
            'customers' => $this->getCustomers(),
            'employees' => $this->getEmployees(),
        ]);
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        return CustomerHistory::query()
            ->has('original')
            ->when($this->keyword, function (Builder $builder, $keyword) {
                $builder->where('name', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%");
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
            ->where('status', '=', CUSTOMER_PENDING)
            ->orderBy('status')
            ->with(['user', 'original', 'original.user'] )
            ->latest('id')
            ->paginate(10);
    }

    public function getEmployees()
    {
        return User::all();
    }


}
