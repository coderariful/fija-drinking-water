<?php

namespace App\Traits;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait CustomerListTrait
{
    public function getCustomerListData(): array
    {
        $booleans = ["true" => true, "false" => false];
        $showDue = $booleans[request('showDue', 'false')];

        $query = Customer::query()
            ->withTransactions()
            ->when(request('keyword'), function (Builder $builder, $keyword) {
                $builder->where('customers.name', 'like', "%$keyword%")
                    ->orWhere('customers.phone', 'like', "%$keyword%");
            })
            ->when(request('start_date'), function (Builder $builder, $start_date) {
                $builder->whereDate('customers.issue_date', '>=', $start_date);
            })
            ->when(request('end_date'), function (Builder $builder, $end_date) {
                $builder->whereDate('customers.issue_date', '<=', $end_date);
            })
            ->when(request('employee_id'), function (Builder $builder, $employee_id) {
                $builder->where('customers.user_id', '=', $employee_id);
            })
            ->when(request('day'), function (Builder $builder) {
                $builder->where(DB::raw('DATE(t.created_at)'), date('Y-m-d'));
            })
            ->when($showDue, function (Builder $builder) {
                // $builder->where(DB::raw("(IFNULL(SUM(t.total_amount),0) - IFNULL(SUM(t.paid_amount),0))"), '>', 0);
                $builder->having(DB::raw("(IFNULL(SUM(t.total_amount),0) - IFNULL(SUM(t.paid_amount),0))"), '>', 0);
            })
            ->when(!is_null(request('status')) && in_array(request('status'), [
                    CUSTOMER_APPROVED, CUSTOMER_REJECTED, CUSTOMER_PENDING
                ]), function ($builder) {
                $builder->where('status', '=', request('status'));
            })
            ->with('user')
            ->orderBy('status')
            ->latest('id');

        $customers = $query->get();

        return compact('customers');
    }


    private function getInactiveCustomerListData()
    {
        return [
            'customers' => Customer::query()
                ->withTransactions()
                ->addSelect(DB::raw('MAX(t.created_at) as last_transaction_date'))
                ->when(request('keyword'), function (Builder $builder, $keyword) {
                    $builder->where(function ($query) use ($keyword) {
                        $query->where('customers.name', 'like', "%$keyword%")
                            ->orWhere('customers.phone', 'like', "%$keyword%");
                    });
                })
                ->when(request('start_date'), function (Builder $builder, $start_date) {
                    $builder->whereDate('customers.issue_date', '>=', $start_date);
                })
                ->when(request('end_date'), function (Builder $builder, $end_date) {
                    $builder->whereDate('customers.issue_date', '<=', $end_date);
                })
                ->when(request('employee_id'), function (Builder $builder, $employee_id) {
                    $builder->where('customers.user_id', '=', $employee_id);
                })
                ->whereDoesntHave('sales', function (Builder $builder) {
                    $builder->whereDate('created_at', '>', today()->subDays(7));
                })
                ->with([
                    'user',
                ])
                //->orderBy('status')
                ->latest(DB::raw('IFNULL(SUM(t.total_amount), 0) - IFNULL(SUM(t.paid_amount), 0)'))
                ->oldest(DB::raw('MAX(t.created_at)'))
                ->latest('customers.created_at')
                ->get()
                ->map(function (Customer $item) {
                    $item->last_transaction_date = $item->last_transaction_date
                        ? Carbon::parse($item->last_transaction_date)
                        : null;
                    return $item;
                })
        ];
    }
}
