<?php

namespace App\Http\Livewire\User;

use App\Models\Customer;
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
    public $showDue=false;

    protected $listeners = [
        'update-customer-index' => '$refresh',
    ];

    public function render(): Factory|View|Application
    {
        return view('livewire.user.customer-index', [
            'customers' => $this->getCustomers(),
        ]);
    }

    public function filterDue($val)
    {
        $this->showDue = $val;
    }

    public function getCustomers(): LengthAwarePaginator|array
    {
        $purchaseWater = ['purchase'=>'whereProductTypeWater'];

        return Customer::query()
            ->where('user_id', '=', auth()->id())
            ->where('status', '=', $this->status)
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
            ->when($this->showDue, function (Builder $builder) {
                $query_sum_sales_total_cost = 'ifnull((select sum(sales.total_cost) from sales where customers.id = sales.customer_id),0)';
                $query_sum_payments_amount = 'ifnull((select sum(payments.amount) from payments where customers.id = payments.customer_id),0)';
                $builder->where(DB::raw("($query_sum_sales_total_cost - $query_sum_payments_amount)"), '>', 0);
            })
            ->orderBy('status')
            ->with('user')
            ->withSum('sales', 'total_cost')
            ->withSum('payments', 'amount')
            ->withSum($purchaseWater, 'in_quantity')
            ->withSum($purchaseWater, 'out_quantity')
            ->latest('id')
            ->paginate(RECORDS_PER_PAGE);
    }
}
