<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Throwable;

class AdminController extends Controller
{
    public function moneyDetails()
    {
        try {
            $title = 'Details of money';
            $totalCollectMoney = 0;
            $totalDueMoney = 0;
            $totalMoney = $totalCollectMoney + $totalDueMoney;
            return view('admin.money.index', compact('title', 'totalMoney', 'totalCollectMoney', 'totalDueMoney'));
        } catch (Throwable $th) {
            throw $th;
        }
    }

    public function migrateUpgrade()
    {
        Artisan::call('migrate', ['--force' => true]);

        return [
            'message' => 'Application upgraded successfully.',
            'output' => array_filter(array_map('trim', explode('\n', Artisan::output())), function ($value) {
                return !empty(trim($value));
            })
        ];
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('clear-compiled');

        return back()->with('success', 'Application cache cleared successfully.');
    }
}
