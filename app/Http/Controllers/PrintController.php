<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\CustomerCardTrait;
use App\Traits\CustomerListTrait;
use App\Traits\SalesHistoryTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class PrintController extends Controller
{
    use CustomerCardTrait, CustomerListTrait, SalesHistoryTrait;

    public function printCard(Request $request, Customer $customer)
    {
        $data = $this->getCustomerCardData($request, $customer);

        if (request('view') == 'on') {
            return view('pdf.card', $data);
        }

        $timestamp = time();

        return Pdf::loadView('pdf.card', $data)->stream("delivery_card_$timestamp.pdf");
    }

    public function customerListPrint()
    {
        $data = $this->getCustomerListData();

        if (request('view') == 'on') {
            return view('pdf.customer', $data);
        }

        return Pdf::loadView('pdf.customer', $data)->setPaper('a4', 'landscape')->stream('customer_list');
    }

    public function salesListPrint(User $user)
    {
        $data = $this->getSalesData($user);

        if (request('view') == 'on') {
            return view('pdf.sales', $data);
        }

        return Pdf::loadView('pdf.sales', $data)->setPaper('a4')->stream('sales');
    }

    public function printInactiveCustomerList()
    {
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '4096M');

        $data = $this->getInactiveCustomerListData();

        if (request('view') == 'on') {
            return view('pdf.customer', $data);
        }

        return Pdf::loadView('pdf.customer', $data)->setPaper('a4', 'landscape')->stream('customer_list');
    }
}
