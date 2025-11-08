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
        $customers = $this->getCustomers();
        return view('livewire.admin.customer-index', [
            'customers' => $customers,
            'employees' => $this->getEmployees(),
        ]);
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        $purchaseWater = ['purchase'=>'whereProductTypeWater'];

        $query = Customer::query()
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
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
            })
            ->when($this->showDue, function (Builder $builder) {
                $query_sum_sales_total_cost = 'ifnull((select sum(sales.total_cost) from sales where customers.id = sales.customer_id),0)';
                $query_sum_payments_amount = 'ifnull((select sum(payments.amount) from payments where customers.id = payments.customer_id),0)';
                $builder->where(DB::raw("($query_sum_sales_total_cost - $query_sum_payments_amount)"), '>', 0);
            })
            ->where('status', '=', $this->status)
            ->orderBy('status')
            ->with('user')
            ->withSum('sales', 'total_cost')
            ->withSum('payments', 'amount')
            ->withSum($purchaseWater, 'in_quantity')
            ->withSum($purchaseWater, 'out_quantity')
            ->latest('id');

        return $query->paginate(RECORDS_PER_PAGE);
    }

    public function getEmployees()
    {
        return User::all();
    }

    function sendToAll()
    {
        dispatch(new SendBulkSmsToAllJob);

        flash('SMS sent to all customers');
    }
}
