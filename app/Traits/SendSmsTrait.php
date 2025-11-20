<?php

namespace App\Traits;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsTemplate;

trait SendSmsTrait
{
    public function sendSms(?Customer $customer, array $data, string $templateName): void
    {
        $send_sms = settings('send_sms');
        if (!is_null($send_sms) && $send_sms == 0) {
            return;
        }

        if ($customer->send_sms) {
            $template = SmsTemplate::firstWhere('template', $templateName);
            $message = SMS::parseTemplate($template->body, $data);
            $history = SMS::saveInHistory($customer->id, $customer->phone, $message, $templateName);
            $sms = new SMS($message);
            $sms->send($customer->phone, true);
        }

        flash("SMS Sent");
    }
}
