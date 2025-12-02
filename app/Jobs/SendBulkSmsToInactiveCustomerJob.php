<?php

namespace App\Jobs;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsSendBulk;
use App\Models\SmsTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Log;

class SendBulkSmsToInactiveCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection|array $customers;
    private null|SmsTemplate|Model $template;

    /**
     * Create a new job instance.
     */
    public function __construct(public $groupId = null, $customerIds = null, $excludeZeroDue = false)
    {
        $this->groupId = $groupId ?? uniqid();

        $alreadySent = SmsSendBulk::select('customer_id')
            ->where('group_id', $this->groupId)
            ->where('type', 'group-chunk-sms')
            ->pluck('customer_id');

        $this->customers = Customer::query()
            ->withTransactions($excludeZeroDue)
            ->addSelect(DB::raw('MAX(t.created_at) as last_transaction_date'))
            ->whereDoesntHave('sales', function (Builder $builder) {
                $builder->whereDate('created_at', '>', today()->subDays(7));
            })
            ->where('send_sms', 1)
            ->where('status', CUSTOMER_APPROVED)
            ->whereNotIn('id', $alreadySent)
            ->when($customerIds, fn($q) => $q->whereIn('customer_id', $customerIds))
            ->get()
            ->filter(fn(Customer $customer) => intval($customer->phone)!=0)
            ->toArray();

        $this->template = SmsTemplate::firstWhere('template', 'inactive-customer-sms');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = $this->customers;

        // dd($customers[0]);

        foreach (array_chunk($customers, 99) as $chunk) {
            $sdk = new SMS();

            $data = [];
            foreach ($chunk as $customer) {
                $params = [
                    "{due_amount}"  => round(floatval($customer['due_amount']), 2),
                    "{jar_stock}"   => round(floatval($customer['jar_stock']), 2),
                ];
                $message = SMS::parseTemplate($this->template->body, $params);
                if (intval($customer['due_amount'])>0 && $customer['send_sms'] == 1) {
                    $data[] = [
                        'mobile' => SMS::validatePhone($customer['phone']),
                        'sms' => $message
                    ];
                }
            }

            //dd($data);

            try {
                $response = $sdk->sendBulk($data, mask: true);

                if (!config('sms.sandbox')) {
                    SmsSendBulk::create([
                        'group_id' => $this->groupId,
                        'type' => 'group-chunk-response',
                        'response' => json_encode($response),
                    ]);
                }
            } catch (\Exception $e) {
                $error = "Error: {$e->getMessage()} File: {$e->getFile()} Line: {$e->getLine()}";
                Log::error($error);
                if (!config('sms.sandbox')) {
                    SmsSendBulk::create(['group_id' => $this->groupId, 'type' => 'group-chunk-error', 'response' => $error]);
                }
            }
        }
    }
}
