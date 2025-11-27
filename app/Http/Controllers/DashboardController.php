<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\SmsHistory;
use App\Models\SmsSendBulk;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->deleteOldSmsLogs();
    }

    public function dashboard()
    {
        //$jar_stock = Purchase::where('product_type', Product::WATER)->selectRaw('SUM(in_quantity) as in_qty, SUM(out_quantity) as out_qty')->first();
        $total_sell_today = Transaction::query()->today()->sum("total_amount");
        $total_collect_today = Transaction::query()
            ->today()
            ->sum("paid_amount");
        $due_today = $total_sell_today - $total_collect_today;

        // $jar_in_qty = Transaction::query()->where('product_type', Product::WATER)->sum('in_quantity');
        // $jar_out_qty = Transaction::query()->where('product_type', Product::WATER)->sum('out_quantity');

        $jar_stock = Transaction::query()
            ->where("product_type", Product::WATER)
            ->select([
                DB::raw(
                    "IFNULL(SUM(in_quantity) - SUM(out_quantity), 0) as total_stock",
                ),
                DB::raw("SUM(in_quantity) as in_qty"),
                DB::raw("SUM(out_quantity) as out_qty"),
            ])
            ->first();

        $total_due =
            Transaction::query()
                ->selectRaw(
                    "COALESCE(SUM(total_amount) - SUM(paid_amount), 0) as due_amount",
                )
                ->first()->due_amount ?? 0;

        /*$total_due = DB::table('transactions')
            ->selectRaw('IFNULL(SUM(total_amount) - SUM(paid_amount), 0) as due_amount')
            ->first()->due_amount ?? 0;*/

        $jar_sale_today = Transaction::query()->today()
            ->where("product_type", Product::WATER)
            ->sum('in_quantity');

        $jar_sale_this_month = Transaction::query()->thisMonth()
            ->where("product_type", Product::WATER)
            ->sum('in_quantity');

        $new_customer = Customer::whereDate("created_at", today())->count();
        $pending_customer = Customer::where("status", Customer::PENDING)->count();

        $salesGraph = $this->getDailySalesGraph();

        //dd($salesGraph->toArray());

        return view("admin.dashboard", [
            "title" => trans("Dashboard"),
            "total_customer" => Customer::count(),
            "total_employee" => User::count() - 1,
            "new_customer" => $new_customer,
            "pending_customer" => $pending_customer,
            "total_jar_stock" => $jar_stock->total_stock ?? 0,
            "total_sell_today" => round($total_sell_today, 2),
            "total_collect_today" => round($total_collect_today, 2),
            "total_due_today" => max(round($due_today, 2), 0),
            "dispenser" => [], //Product::where('type', Product::DISPENSER)->withCount('sales')->get(),
            "jar_sale_today" => round($jar_sale_today, 2),
            "jar_sale_this_month" => round($jar_sale_this_month, 2),
            "total_due" => max(round($total_due, 2), 0),
            "salesGraph" => $salesGraph,
        ]);
    }

    public function userDashboard()
    {
        $user_id = auth()->id();

        $jar_stock = Transaction::query()
            ->whereRelation("customer", "user_id", $user_id)
            ->where("product_type", Product::WATER)
            ->selectRaw("SUM(in_quantity) as in_qty, SUM(out_quantity) as out_qty")
            ->first();
        $total_sell_today = Transaction::query()->today()
            ->where("user_id", $user_id)
            ->sum("total_amount");
        $total_collect_today = Transaction::query()->today()
            ->where("user_id", $user_id)
            ->sum("paid_amount");
        $due_today = $total_sell_today - $total_collect_today;

        $total_sell = Transaction::query()
            ->where("user_id", $user_id)
            ->sum("total_amount");
        $total_paid = Transaction::query()
            ->where("user_id", $user_id)
            ->sum("paid_amount");

        $jar_sale_today = Transaction::query()->today()->whereUserId($user_id)
            ->where("product_type", Product::WATER)
            ->sum('in_quantity');

        $jar_sale_this_month = Transaction::query()->thisMonth()->whereUserId($user_id)
            ->where("product_type", Product::WATER)
            ->sum('in_quantity');

        return view("user.dashboard", [
            "title" => trans("Dashboard"),
            "total_customer" => Customer::where("user_id", $user_id)
                ->where("user_id", $user_id)
                ->count(),
            "total_employee" => 0,
            "new_customer" => Customer::where("user_id", $user_id)
                ->whereDate("created_at", today())
                ->count(),
            "pending_customer" => Customer::where("user_id", $user_id)
                ->where("status", Customer::PENDING)
                ->count(),
            "total_jar_stock" => $jar_stock->in_qty - $jar_stock->out_qty,
            "total_sell_today" => round($total_sell_today, 2),
            "total_collect_today" => round($total_collect_today, 2),
            "total_due_today" => max(round($due_today, 2), 0),
            "dispenser" => [], //Product::where('type', Product::DISPENSER)->whereRelation('sales', 'user_id', $user_id)->withCount('sales')->get(),
            "jar_sale_today" => $jar_sale_today,
            "jar_sale_this_month" => $jar_sale_this_month,
            "total_due" => max($total_sell - $total_paid, 0),
            "firstDayOfMonth" => today()->firstOfMonth()->format("Y-m-d"),
            "lastDayOfMonth" => today()->lastOfMonth()->format("Y-m-d"),
            "today" => today()->format("Y-m-d"),
        ]);
    }

    private function deleteOldSmsLogs(): void
    {
        $ttl = now()->addHours(24);
        Cache::lock('delete-old-sms-logs-lock', $ttl)->get(function () {
            $time = today()->subDays(30);
            $smsHistoryQuery = SmsHistory::query()->whereDate("created_at", "<", $time);
            $bulkSmsQuery = SmsSendBulk::query()->whereDate("created_at", "<", $time);
            if ($bulkSmsQuery->count() > 0) {
                $bulkSmsQuery->delete();
            }
            if ($smsHistoryQuery->count() > 0) {
                $smsHistoryQuery->delete();
            }
        });

    }

    public function getDailySalesGraph()
    {
        $data = Transaction::query()
            ->select([
                DB::raw('DATE(created_at) as trx_date'),
                DB::raw('IFNULL(SUM(total_amount), 0) as total_sale'),
                DB::raw('IFNULL(SUM(paid_amount), 0) as total_paid'),
            ])
            ->where(
                'created_at',
                '>=',
                now()->subDays(29)->startOfDay(),
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('trx_date', 'ASC')
            ->get();

        return $data->map(function (Transaction $item) {
            return [
                'date' => Carbon::parse($item->trx_date)->format('M-d'),
                'sale' => round($item->total_sale, 2),
                'paid' => round($item->total_paid, 2),
                'due' => max(round($item->total_sale - $item->total_paid, 2), 0),
            ];
        });
    }
}
