<?php

namespace App\Jobs;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsSendBulk;
use App\Models\SmsTemplate;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Log;

class SendBulkSmsToAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array|Collection $customers;
    private null|SmsTemplate $template;

    /**
     * Create a new job instance.
     */
    public function __construct(public $groupId = null, ?array $customerIds = null, bool $excludeZeroDue = false)
    {
        $this->groupId = $groupId ?? uniqid();

        $alreadySent = SmsSendBulk::select('customer_id')
            ->where('group_id', $this->groupId)
            ->where('type', 'group-chunk-sms')
            ->pluck('customer_id');

        $this->customers = Customer::query()
            ->withTransactions($excludeZeroDue)
            ->where('send_sms', 1)
            ->where('status', CUSTOMER_APPROVED)
            ->whereNotIn('id', $alreadySent)
            ->when($customerIds, fn($q) => $q->whereIn('customer_id', $customerIds))
            ->oldest()
            ->get();

        $this->template = SmsTemplate::firstWhere('template', 'due-sms');

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = $this->customers->filter(fn(Customer $customer) => intval($customer->phone)!=0)->all();

        foreach (array_chunk($customers, 99) as $chunk) {
            $sdk = new SMS();

            foreach ($chunk as $customer) {
                $params = SMS::getParameters($customer, 'due-sms');
                $message = SMS::parseTemplate($this->template->body, $params);
                if (intval($customer->due_amount)>0 && $customer->send_sms) {
                    $sdk->addBulkSmsMessage([
                        'mobile' =>  $customer->phone,
                        'sms' => $message
                    ]);
                }
            }

            try {
                $response = $sdk->sendBulk(mask: true);

                //if ($response->status) {
                //    flash($response->message);
                //}

                if (!config('sms.sandbox')) {
                    SmsSendBulk::create([
                        'group_id' => $this->groupId,
                        'type' => 'group-chunk-response',
                        'response' => json_encode($response),
                    ]);
                }
            } catch (Exception $e) {
                $error = "Error: {$e->getMessage()} File: {$e->getFile()} Line: {$e->getLine()}";
                Log::error($error);
                if (!config('sms.sandbox')) {
                    SmsSendBulk::create(['group_id' => $this->groupId, 'type' => 'group-chunk-error', 'response' => $error]);
                }
            }
        }

    }
}
