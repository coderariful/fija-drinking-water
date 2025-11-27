<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        SmsTemplate::firstOrCreate(['template' => 'new-customer-sms'], [
            'name' => 'New Customer',
            'body' => 'প্রিয় গ্রাহক, আপনাকে স্বাগতম। ফিজা ড্রিংকিং ওয়াটার -এ আপনার নামে একটি নতুন হিসাব হয়েছে।',
            'params' => [
                 "{customer_name}" => "Customer Name",
                 "{customer_phone}" => "Customer Phone",
            ]
        ]);

        SmsTemplate::firstOrCreate(['template' => 'customer-monthly-sms'], [
            'name'     => 'Customer Monthly SMS',
            'body'     => 'প্রিয় গ্রাহক, আপনার {month_name} মাসের বকেয়া {due_amount}Tk বিল পরিশোধ করুন। বিকাশ নম্বর - {bkash_number}।',
            'params'   => [
                "{month_name}" => "Current Month Name",
                "{due_amount}" => "Due Amount",
                "{bkash_number}" => "Bkash Number",
            ]
        ]);

        SmsTemplate::firstOrCreate(['template' => 'customer-daily-sms'], [
            'name'     => 'Customer Daily SMS',
            'body'     => 'প্রিয় গ্রাহক, বিল {jar_count}*{jar_rate}={bill_amount}Tk জার ফেরত {jar_return}, স্টক {jar_stock}, পেমেন্ট {paid_amount}, বকেয়া {due_amount}Tk।',
            'params'   => [
                "{bill_amount}" => "Bill Amount",
                "{paid_amount}" => "Paid Amount",
                "{due_amount}"  => "Due Amount",
                "{jar_stock}"   => "Jar Stock",
                "{jar_return}"  => "Jar Return",
                "{jar_count}"   => "Jar Count",
                "{jar_rate}"    => "Jar Rate",
            ]
        ]);

        SmsTemplate::firstOrCreate(['template' => 'water-sms'], [
            'name'     => 'Water SMS',
            'body'     => 'প্রিয় গ্রাহক, পানির বিল {sale_count}*{sale_rate}={bill_amount}Tk। জার ফেরত {jar_return}, স্টক {jar_stock}, পেমেন্ট {paid_amount}, বকেয়া {due_amount}Tk।',
            'params'   => [
                "{sale_count}"  => "Sale Count",
                "{sale_rate}"   => "Sale Rate",
                "{bill_amount}" => "Bill Amount",
                "{paid_amount}" => "Paid Amount",
                "{due_amount}"  => "Due Amount",
                "{jar_stock}"   => "Jar Stock",
                "{jar_return}"  => "Jar Return",
            ]
        ]);

        SmsTemplate::firstOrCreate(['template' => 'dispenser-sms'], [
            'name' => 'Dispenser SMS',
            'body' => 'প্রিয় গ্রাহক, ডিসপেন্সার বিল {sale_count}*{sale_rate}={bill_amount}Tk। পেমেন্ট {paid_amount}, বকেয়া {due_amount}Tk।',
            'params' => [
                "{sale_count}"  => "Sale Count",
                "{sale_rate}"   => "Sale Rate",
                "{bill_amount}" => "Bill Amount",
                "{paid_amount}" => "Paid Amount",
                "{due_amount}"  => "Due Amount",
            ]
        ]);

        SmsTemplate::firstOrCreate(['template' => 'payment-sms'], [
            'name' => 'Payment SMS',
            'body' => 'প্রিয় গ্রাহক, আপনার পেমেন্ট {paid_amount}, বকেয়া {due_amount}Tk।',
            'params' => [
                "{paid_amount}" => "Paid Amount",
                "{due_amount}"  => "Due Amount",
            ]
        ]);

        SmsTemplate::firstOrCreate(['template' => 'due-sms'], [
            'name' => 'Due SMS',
            'body' => 'প্রিয় গ্রাহক, আপনার বকেয়া {due_amount}Tk।',
            'params' => [
                "{due_amount}"  => "Due Amount",
            ]
        ]);


        SmsTemplate::firstOrCreate(['template' => 'inactive-customer-sms'], [
            'name' => 'Inactive Customer SMS',
            'body' => 'প্রিয় গ্রাহক, আপনার বকেয়া {due_amount}Tk এবং জার স্টক {jar_stock}।',
            'params' => [
                "{due_amount}"  => "Due Amount",
                "{jar_stock}"   => "Jar Stock",
            ]
        ]);
    }
}
