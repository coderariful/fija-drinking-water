<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerPhoneNumberExport implements FromCollection
{
    public function collection()
    {
        return Customer::query()
            ->select('phone')
            ->whereNotNull('phone')
            ->get()->filter(function ($customer) {
                return !empty($customer->phone) && intval($customer->phone) != 0;
            });
    }
}
