<?php

namespace App\Http\Controllers;

use App\Exports\CustomerPhoneNumberExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

class ExportController extends Controller
{
    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportCustomerPhoneNumber()
    {
        return Excel::download(
            new CustomerPhoneNumberExport,
            'customer_phone_numbers.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }
}
