<?php

use App\Models\SmsTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        SmsTemplate::firstOrCreate(['template' => 'inactive-customer-sms'], [
            'name' => 'Inactive Customer SMS',
            'body' => 'প্রিয় গ্রাহক, আপনার বকেয়া {due_amount}Tk এবং জার স্টক {jar_stock}।',
            'params' => [
                "{due_amount}"  => "Due Amount",
                "{jar_stock}"   => "Jar Stock",
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
