<?php

namespace App\Http\Livewire\Admin;

use App\Jobs\SendBulkSmsToAllJob;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerIndex extends Component
{
    use WithPagination;

    public $title;
    public $status;
    public $keyword;
    public $start_date;
    public $end_date;
    public $employee_id;
    public $showDue=false;

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public array $pageCustomerIds = [];

    public function update_status(Customer $customer, $status): void
    {
        $customer->update(['status' => $status]);

        flash("Customer " . $customer::STATUS[$status]);
    }

    public function filterDue($val): void
    {
        $this->showDue = $val;
    }

    public function render(): Factory|View|Application
    {
        $printUrl = route('print.customer-list');

        $customers = $this->getCustomers();
        $this->pageCustomerIds = collect($customers->items())->pluck('id')->all();

        return view('livewire.admin.customer-index', [
            'customers' => $customers,
            'employees' => $this->getEmployees(),
            'printUrl' => $printUrl,
        ]);
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        $query = Customer::query()
            ->withTransactions($this->showDue)
            ->when($this->keyword, function (Builder $builder, $keyword) {
                $builder->where('customers.name', 'like', "%$keyword%")
                    ->orWhere('customers.phone', 'like', "%$keyword%");
            })
            ->when($this->start_date, function (Builder $builder, $start_date) {
                $builder->whereDate('customers.issue_date', '>=', $start_date);
            })
            ->when($this->end_date, function (Builder $builder, $end_date) {
                $builder->whereDate('customers.issue_date', '<=', $end_date);
            })
            ->when($this->employee_id, function (Builder $builder, $employee_id) {
                $builder->where('customers.user_id', '=', $employee_id);
            })
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(customers.created_at)'), date('Y-m-d'));
            })
            ->where('customers.status', '=', $this->status)
            ->orderBy('customers.status')
            ->with('user')
            ->latest('customers.id');

        // dd($query->take(10)->get());

        return $query->paginate(RECORDS_PER_PAGE);
    }

    public function getEmployees(): Collection|array
    {
        return User::all();
    }

    function sendToAll(): void
    {
        dispatch(new SendBulkSmsToAllJob(excludeZeroDue: $this->showDue));

        flash('SMS sent to all customers');
    }

    function sendToCurrentPage(): void
    {
        dispatch(new SendBulkSmsToAllJob(
            customerIds: $this->pageCustomerIds,
            excludeZeroDue: $this->showDue
        ));

        flash('SMS sent to current page customers');
    }
}
