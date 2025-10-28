<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GeneralSettings;
use App\Models\LogoSettings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
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
}
