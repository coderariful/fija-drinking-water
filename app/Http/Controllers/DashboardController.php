<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;

class DashboardController extends Controller
{
    public function dashboard()
    {
        //$jar_stock = Purchase::where('product_type', Product::WATER)->selectRaw('SUM(in_quantity) as in_qty, SUM(out_quantity) as out_qty')->first();
        $total_sell_today = Transaction::common()->today()->sum('total_amount');
        $total_collect_today =  Transaction::common()->today()->sum('paid_amount');
        $due_today = $total_sell_today - $total_collect_today;

        $jar_in_qty = Transaction::common()->where('product_type', Product::WATER)->sum('in_quantity');
        $jar_out_qty = Transaction::common()->where('product_type', Product::WATER)->sum('out_quantity');

        $total_due = Transaction::common()->sum('total_amount') - Transaction::common()->sum('paid_amount');

        return view('admin.dashboard', [
            'title' => trans('Dashboard'),
            'total_customer' => Customer::count(),
            'total_employee' => User::count() - 1,
            'new_customer' => Customer::whereDate('created_at', today())->count(),
            'pending_customer' => Customer::where('status', Customer::PENDING)->count(),
            'total_jar_stock' => $jar_in_qty - $jar_out_qty, //$jar_stock->in_qty - $jar_stock->out_qty,
            'total_sell_today' => $total_sell_today,
            'total_collect_today' => $total_collect_today,
            'total_due_today' => max($due_today, 0),
            'dispenser' => [], //Product::where('type', Product::DISPENSER)->withCount('sales')->get(),
            'jar_sale_today' => Transaction::common()->today()->where('product_type', Product::WATER)->count(),
            'jar_sale_this_month' => Transaction::common()->thisMonth()->where('product_type', Product::WATER)->count(),
            'total_due' => max($total_due, 0),
        ]);
    }

    public function userDashboard()
    {
        $user_id = auth()->id();

        $jar_stock = Transaction::common()->whereRelation('customer', 'user_id', $user_id)->where('product_type', Product::WATER)->selectRaw('SUM(in_quantity) as in_qty, SUM(out_quantity) as out_qty')->first();
        $total_sell_today = Transaction::common()->where('user_id', $user_id)->today()->sum('total_amount');
        $total_collect_today =  Transaction::common()->where('user_id', $user_id)->today()->sum('paid_amount');
        $due_today = $total_sell_today - $total_collect_today;

        $total_sell = Transaction::common()->where('user_id', $user_id)->sum('total_amount');
        $total_paid = Transaction::common()->where('user_id', $user_id)->sum('paid_amount');

        return view('user.dashboard', [
            'title' => trans('Dashboard'),
            'total_customer' => Customer::where('user_id', $user_id)->where('user_id', $user_id)->count(),
            'total_employee' => 0,
            'new_customer' => Customer::where('user_id', $user_id)->whereDate('created_at', today())->count(),
            'pending_customer' => Customer::where('user_id', $user_id)->where('status', Customer::PENDING)->count(),
            'total_jar_stock' => $jar_stock->in_qty - $jar_stock->out_qty,
            'total_sell_today' => $total_sell_today,
            'total_collect_today' => $total_collect_today,
            'total_due_today' => max($due_today, 0),
            'dispenser' =>  [], //Product::where('type', Product::DISPENSER)->whereRelation('sales', 'user_id', $user_id)->withCount('sales')->get(),
            'jar_sale_today' => Transaction::common()->today()->whereUserId($user_id)->where('product_type', Product::WATER)->count(),
            'jar_sale_this_month' => Transaction::common()->thisMonth()->whereUserId($user_id)->where('product_type', Product::WATER)->count(),
            'total_due' => max($total_sell - $total_paid, 0),
            'firstDayOfMonth' => today()->firstOfMonth()->format('Y-m-d'),
            'lastDayOfMonth' =>  today()->lastOfMonth()->format('Y-m-d'),
            'today' => today()->format('Y-m-d'),
        ]);
    }
}
