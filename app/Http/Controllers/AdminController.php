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
            'output' => trim(Artisan::output())
        ];
    }
}
