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

    public function update_status(Customer $customer, $status)
    {
        $customer->update(['status' => $status]);

        flash("Customer " . $customer::STATUS[$status]);
    }

    public function filterDue($val)
    {
        $this->showDue = $val;
    }

    public function render(): Factory|View|Application
    {
        $printUrl = route('print.customer-list', [
            'status' => $this->status,
            'keyword' => $this->keyword,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'employee_id' => $this->employee_id,
            'showDue' => $this->showDue,
            'view' => 'on',
        ]);

        return view('livewire.admin.customer-index', [
            'customers' => $this->getCustomers(),
            'employees' => $this->getEmployees(),
            'printUrl' => $printUrl,
        ]);
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        $query = Customer::query()
            ->withTransactions()
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
                $builder->where('customers.user_id', '=', $employee_id);
            })
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
            })
            ->when($this->showDue, function (Builder $builder) {
                // $builder->where(DB::raw("(IFNULL(SUM(t.total_amount),0) - IFNULL(SUM(t.paid_amount),0))"), '>', 0);
                $builder->having(DB::raw("(IFNULL(SUM(t.total_amount),0) - IFNULL(SUM(t.paid_amount),0))"), '>', 0);
            })
            ->where('status', '=', $this->status)
            ->orderBy('status')
            ->with('user')
            ->latest('id');

        // dd($query->take(10)->get());

        return $query->paginate(RECORDS_PER_PAGE);
    }

    public function getEmployees(): Collection|array
    {
        return User::all();
    }

    function sendToAll(): void
    {
        dispatch(new SendBulkSmsToAllJob);

        flash('SMS sent to all customers');
    }
}
