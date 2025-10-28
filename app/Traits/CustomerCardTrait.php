<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

trait CustomerCardTrait
{
    public function getCustomerCardData($request, $customer): array
    {
        $date = today();

        if ($request->has('filter')) {
            if ($request->get('filter') == 'month'){
                $date = date(sprintf("%s-%s-1", $request->year, $request->month));
                $date = Carbon::parse($date)->startOfMonth()->toDateString();
            }
            if($request->get('filter') == 'date') {
                $date = Carbon::parse($request->date[0])->toDateString();
            }
        }

        $previous_sales = $customer->sales()
            ->where('product_type', PRODUCT_WATER)
            ->whereDate('created_at', '<', $date)
            ->sum('total_cost');

        $previous_payments = $customer->payments()
            ->whereDate('created_at', '<', $date)
            ->sum('amount');

        $previous_due = round($previous_sales - $previous_payments);

        $query = $customer->purchase()
            ->with(['sale', 'payment'])
            ->orderBy('created_at');
        if ($request->has('filter')) {
            if ($request->get('filter') == 'month'){
                $query->where(DB::raw('MONTH(created_at)'), $request->month)
                    ->where(DB::raw('YEAR(created_at)'), $request->year);
            }
            if($request->get('filter') == 'date'){
                $query->where(DB::raw('DATE(created_at)'), '>=', $request->date[0])
                    ->where(DB::raw('DATE(created_at)'), '<=',$request->date[1]);
            }
        }
        $histories = $query->get();
        $dispenser = $customer->dispenserAll()->toArray();

        return compact(
            'histories',
            'customer',
            'dispenser',
            'previous_due'
        );
    }
}
