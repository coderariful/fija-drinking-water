<?php

namespace App\Traits;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait SalesHistoryTrait
{
    protected function showCurrentSalesFilter(): array|string|null
    {
        return match (request('filter')) {
            'day' => match (request('day')) {
                'today' => trans('Today'),
                'yesterday' => trans('Yesterday'),
                'last_three_days' => trans('Last Three Days'),
                'last_seven_days' => trans('Last Seven Days'),
            },
            'date' => join(' ', [
                Carbon::parse(request('date',['',''])[0])->format('d-M-Y'),
                trans('to'),
                Carbon::parse(request('date',['',''])[1])->format('d-M-Y')
            ]),
            'month' => join(', ', [
                Carbon::create()->day(1)->month(request('month'))->monthName,
                request('year')
            ]),
        };
    }

    private function getSalesHistories(array $dates, $user): ?Collection
    {
        return $user?->sales()
            ->where('product_type', PRODUCT_WATER)
            ->when(request('month'), fn(Builder $query, $month) => $query->where(DB::raw('MONTH(purchases.created_at)'), $month))
            ->when(request('year'), fn(Builder $query, $year) => $query->where(DB::raw('YEAR(purchases.created_at)'), $year))
            ->when(request('date', ['',''])[0], fn(Builder $query, $start_date) => $query->where(DB::raw('DATE(purchases.created_at)'), '>=', $start_date))
            ->when(request('date', ['',''])[1], fn(Builder $query, $end_date) => $query->where(DB::raw('DATE(purchases.created_at)'), '<=',$end_date))
            ->when(request('day'), fn (Builder $query, $day) => $query->where(DB::raw('DATE(purchases.created_at)'), '>=', $dates[$day]))
            ->when(request('customer_id'), fn(Builder $query, $customer_id) => $query->where(DB::raw('customers.id'), $customer_id))
            ->with(['customer', 'payment', 'sale'])
            ->get();
    }

    private function getPreviousSalesHistories(array $dates, $user): object
    {
        $date = today();

        if (request('filter') == 'month'){
            $date = date(sprintf("%s-%s-1", request('year'), request('month')));
            $date = Carbon::parse($date)->startOfMonth()->toDateString();
        }
        if(request('filter') == 'date') {
            $date = Carbon::parse(request('date',[''])[0])->toDateString();
        }
        if (request('filter') == 'day') {
            $date = $dates[request('day')];
        }

        $previous_sales = $user?->sales()
            ->where('product_type', PRODUCT_WATER)
            ->whereDate('sales.created_at', '<', $date)
            ->sum('total_cost');

        $previous_payments = $user?->payments()
            ->whereDate('payments.created_at', '<', $date)
            ->sum('amount');

        $purchases = $user?->purchases()
            ->whereDate('purchases.created_at', '<', $date);

        return (object)[
            'sales' => round((int)$previous_sales, 2),
            'payments' => round((int)$previous_payments, 2),
            'jar_in' => (int)$purchases?->sum('in_quantity'),
            'jar_out' => (int)$purchases?->sum('out_quantity'),
        ];
    }

    public function getSalesData($user): array
    {
        $dates = [
            'today' => today()->format('Y-m-d'),
            'yesterday' => today()->subDay()->format('Y-m-d'),
            'last_three_days' => today()->subDays(3)->format('Y-m-d'),
            'last_seven_days' => today()->subDays(7)->format('Y-m-d'),
        ];

        $histories = $this->getSalesHistories($dates, $user);
        $saleIds = $histories?->pluck('sale_id') ?? [];
        $paymentIds = $histories?->pluck('payment_id') ?? [];

        $groups = $histories?->groupBy('customer_id') ?? [];
        $previous = $this->getPreviousSalesHistories($dates, $user);

        $total_sell = Transaction::where('user_id', $user?->id)->sum('total_cost');
        $total_paid = Transaction::where('user_id', $user?->id)->sum('amount');

        return [
            'groups' => $groups,
            'histories' => $histories,
            'customers' => $user->customer ?? [],
            'jar_in_previous' => $previous->jar_in,
            'jar_out_previous' => $previous->jar_out,
            'jar_in_count' => $histories?->sum('in_quantity'),
            'jar_out_count' => $histories?->sum('out_quantity'),
            'sell_amount' => Transaction::whereIn('id', $saleIds)->sum('total_cost'),
            'collection_amount' => Transaction::whereIn('id', $paymentIds)->sum('amount'),
            'customer'=> Customer::when(request('customer_id'), fn($q, $id) => $q->where('id', $id))->first(),
            'showCurrentFilter' => $this->showCurrentSalesFilter(),
            'customer_id' => request('customer_id'),
            'user' => $user,
            'total_due' => max($total_sell - $total_paid, 0),
        ];
    }
}
