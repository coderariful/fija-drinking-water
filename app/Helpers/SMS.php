<?php

namespace App\Helpers;

use App\Models\Customer;
use App\Models\SmsHistory;
use Illuminate\Support\Facades\File;

class SMS
{
    private string $apiEmail;
    private string $apiSecret;

    private string $smsMask;
    private string $apiUrl;

    public array $bulkSmsData = [];

    public function __construct(private ?string $message = null)
    {
        $this->apiUrl = config('sms.endpoint');
        $this->apiEmail = config('sms.email');
        $this->apiSecret = config('sms.secret');
        $this->smsMask = config('sms.mask');
    }

    public static function parseTemplate(string $template, array $data = []): string
    {
        $message = $template;
        foreach ($data as $param => $value) {
            $message = preg_replace("/$param/", $value, $message);
        }

        return $message;
    }

    public static function getParameters(Customer $customer, string $templateName): array
    {
        if ($templateName === 'customer-daily-sms') {
            $params = [
                "{customer_name}"  => $customer->name,
                "{customer_phone}" => $customer->phone,
            ];
        }
        if ($templateName === 'customer-daily-sms') {
            $params = [
                "{bill_amount}" => (int) $customer->sales()->whereDate('created_at', today())->sum('total_amount'),
                "{paid_amount}" => (int) $customer->sales()->whereDate('created_at', today())->sum('paid_amount'),
                "{due_amount}"  => intval($customer->getDueAmount()),
                "{jar_stock}"   => intval($customer->getJarStock()),
                "{jar_return}"  => (int) $customer->sales()->whereDate('created_at', today())->sum('out_quantity'),
                "{jar_count}"   => (int) $customer->sales()->whereDate('created_at', today())->sum('in_quantity'),
                "{jar_rate}"    => (int) $customer->sales()->whereDate('created_at', today())->latest('id')->first()?->rate,
            ];
        }
        if ($templateName === 'due-sms') {
            $params = [
                "{due_amount}"  => intval($customer->getDueAmount()),
            ];
        }
        if ($templateName === 'customer-monthly-sms') {
            $params = [
                "{month_name}"   => banglaMonth(today()->monthName),
                "{due_amount}"   => intval($customer->getDueAmount()),
                "{bkash_number}" => config('sms.bkash_number'),
            ];
        }
        return $params ?? [];
    }

    public function send(string|int $phone_number, bool|string $mask = null)
    {
        $phone_number = strlen($phone_number) == 11 ? "88$phone_number" : (strlen($phone_number) == 10 ? "880$phone_number" : $phone_number);
        $smsMask = is_string($mask) ? $mask : ($mask ? $this->smsMask : 'Non-Masking');
        $message = $this->message;

        if (config('sms.enabled')) {
            if (config('sms.sandbox')) {
                $message ="Recipient: $phone_number\n---\n$this->message";
                $phone_number = config('sms.test_number');
            }

            $requestBody = [
                'email'    => $this->apiEmail,
                'password' => $this->apiSecret,
                'method'   => 'send_sms',
                'mobile'   => [$phone_number],
                'message'  => $message,
                'mask'     => $smsMask
            ];

            $response = httpPostCurl($this->apiUrl, $requestBody);

            return json_decode($response);
        }

        File::append(base_path('sms.log'), "\n\nRecipient: $phone_number\nFrom: $smsMask\nMessage:\n$this->message");

        return (object) ['status' => true];
    }

    public function sendBulk($data = null,  bool|string $mask = null)
    {
        $smsMask = is_string($mask) ? $mask : ($mask ? $this->smsMask : 'Non-Masking');

        if (config('sms.enabled')) {
            // if (config('sms.sandbox')) {
            //     $phone_number = config('sms.test_number');
            //     $message ="Recipient: $phone_number\n\n$this->message";
            // }

            $bulkData = $data ?? $this->bulkSmsData;
            $data = json_encode($bulkData);

            $requestBody = [
                'email'    => $this->apiEmail,
                'password' => $this->apiSecret,
                'method'   => 'send_multi_sms',
                'mask'     => $smsMask,
                'data'     => $data,
            ];

            $response = httpPostCurl($this->apiUrl, $requestBody);

            return json_decode($response);
        }

        File::append(base_path('sms.log'), "\n\nRecipient: Bulk SMS\nFrom: $smsMask\nDATA:\n" . json_encode($data));

        return (object) ['status' => true];
    }

    public function setMessage(string $message): SMS
    {
        $this->message = $message;
        return $this;
    }

    public static function saveInHistory(int $customer_id, string $phone, string $message, string $template)
    {
        return SmsHistory::create([
            'customer_id' => $customer_id,
            'template' => $template,
            'message' => $message,
            'phone' => $phone,
        ]);
    }

    public function addBulkSmsMessage(array $data): void
    {
        $mobile = $data['mobile'];
        $data['mobile'] = strlen($mobile) == 11 ? "88$mobile" : (strlen($mobile) == 10 ? "880$mobile" : $mobile);
        $this->bulkSmsData[] = $data;
    }
}
