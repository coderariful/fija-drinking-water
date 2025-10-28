<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\SmsCron;
use App\Models\SmsTemplate;
use Exception;
use Illuminate\Support\Facades\Log;

class CronSMS
{
    public static function dailySMS(): void
    {
        $send_sms = settings('send_sms');
        if (!is_null($send_sms) && $send_sms == 0) {
            return;
        }

        $customers = Customer::where('billing_type', BILLING_DAILY)
            ->whereNotIn('id', SmsCron::whereDate('created_at', today())->select('customer_id'))
            ->get();

        $template = SmsTemplate::firstWhere('template', 'customer-daily-sms');
        foreach ($customers as $customer) {
            if ($customer->send_sms) {
                try {
                    $parameters = SMS::getParameters($customer, 'customer-daily-sms');

                    $message = SMS::parseTemplate($template->body, $parameters);


                    $sms = new SMS($message);
                    $response = $sms->send($customer->phone, true);

                    $history = SMS::saveInHistory($customer->id, $customer->phone, $message, 'customer-daily-sms');

                    SmsCron::create([
                        'customer_id' => $customer->id,
                        'history_id' => $history->id,
                        'type' => 'daily',
                        'response' => json_encode($response),
                    ]);
                } catch (Exception $e) {
                    SmsCron::create([
                        'customer_id' => $customer->id,
                        'history_id' => 0,
                        'type' => 'daily',
                        'response' => $e->getMessage(),
                    ]);
                    Log::warning($e->getMessage());
                }
            }
        }
    }

    public static function monthlySMS(): void
    {
        $send_sms = settings('send_sms');
        if (!is_null($send_sms) && $send_sms == 0) {
            return;
        }

        $customers = Customer::where('billing_type', BILLING_MONTHLY)
            ->whereNotIn('id', SmsCron::whereDate('created_at', today())->select('customer_id'))
            ->get();

        $template = SmsTemplate::firstWhere('template', 'customer-monthly-sms');
        foreach ($customers as $customer) {
            if ($customer->send_sms) {
                try {
                    $parameters = SMS::getParameters($customer, 'customer-monthly-sms');

                    $message = SMS::parseTemplate($template->body, $parameters);


                    $sms = new SMS($message);
                    $response = $sms->send($customer->phone, true);

                    $history = SMS::saveInHistory($customer->id, $customer->phone, $message, 'customer-monthly-sms');

                    SmsCron::create([
                        'customer_id' => $customer->id,
                        'history_id' => $history->id,
                        'type' => 'monthly',
                        'response' => json_encode($response),
                    ]);
                } catch (Exception $e) {
                    SmsCron::create([
                        'customer_id' => $customer->id,
                        'history_id' => 0,
                        'type' => 'monthly',
                        'response' => $e->getMessage(),
                    ]);
                    Log::warning($e->getMessage());
                }
            }
        }
    }
}
