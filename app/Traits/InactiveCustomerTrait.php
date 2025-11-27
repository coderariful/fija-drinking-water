<?php

namespace App\Traits;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait InactiveCustomerTrait
{
    public function getCustomersQuery(): LengthAwarePaginator|array
    {
        return Customer::query()
            ->withTransactions()
            ->addSelect(DB::raw('MAX(t.created_at) as last_transaction_date'))
            ->when($this->keyword, function (Builder $builder, $keyword) {
                $builder->where(function ($query) use ($keyword) {
                    $query->where('customers.name', 'like', "%$keyword%")
                        ->orWhere('customers.phone', 'like', "%$keyword%");
                });
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
                $builder->where(DB::raw('DATE(created_at)'), date('Y-m-d'));
            })
            // ->havingRaw('MAX(t.created_at) > NOW() - INTERVAL 7 DAY')->orHavingRaw('MAX(t.created_at)')
            ->whereDoesntHave('sales', function (Builder $builder) {
                $builder->whereDate('created_at', '>', today()->subDays(7));
            })
            ->with(['user'])
            //->orderBy('status')
            ->latest(DB::raw('IFNULL(SUM(t.total_amount), 0) - IFNULL(SUM(t.paid_amount), 0)'))
            ->oldest(DB::raw('MAX(t.created_at)'))
            ->latest('customers.created_at');
    }
}
