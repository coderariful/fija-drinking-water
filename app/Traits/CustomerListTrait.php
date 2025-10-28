<?php

namespace App\Traits;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait CustomerListTrait
{
    public function getCustomerListData(): array
    {
        $booleans = ["true" => true, "false" => false];
        $showDue = $booleans[request('showDue', 'false')];

        $customers = Customer::query()
            ->when(request('keyword'), function (Builder $builder, $keyword) {
                $builder->where('name', 'like', "%$keyword%")
                    ->orWhere('phone', 'like', "%$keyword%");
            })
            ->when(request('start_date'), function (Builder $builder, $start_date) {
                $builder->whereDate('issue_date', '>=', $start_date);
            })
            ->when(request('end_date'), function (Builder $builder, $end_date) {
                $builder->whereDate('issue_date', '<=', $end_date);
            })
            ->when(request('employee_id'), function (Builder $builder, $employee_id) {
                $builder->where('user_id', '=', $employee_id);
            })
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
            })
            ->when(!is_null(request('status')) && in_array(request('status'), [
                    CUSTOMER_APPROVED, CUSTOMER_REJECTED, CUSTOMER_PENDING
                ]), function ($builder) {
                $builder->where('status', '=', request('status'));
            })
            ->when($showDue, function (Builder $builder) {
                $query_sum_sales_total_cost = 'ifnull((select sum(sales.total_cost) from sales where customers.id = sales.customer_id),0)';
                $query_sum_payments_amount = 'ifnull((select sum(payments.amount) from payments where customers.id = payments.customer_id),0)';
                $builder->where(DB::raw("($query_sum_sales_total_cost - $query_sum_payments_amount)"), '>', 0);
            })
            ->orderBy('status')
            ->with('user')
            ->latest('id')
            ->get();

        return compact('customers');
    }
}
