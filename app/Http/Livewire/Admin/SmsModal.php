<?php

namespace App\Http\Livewire\Admin;

use App\Helpers\SMS;
use App\Models\Customer;
use App\Models\SmsTemplate;
use App\Traits\SendSmsTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class SmsModal extends Component
{
    use SendSmsTrait;

    public ?Customer $customer = null;

    public $message;

    public ?array $alert = null;

    protected $listeners = [
        'open-modal' => 'loadData'
    ];

    public function send(string $templateName = null): void
    {
        if (in_array($templateName, ['customer-monthly-sms', 'customer-daily-sms'])){
            $template = SmsTemplate::firstWhere('template', $templateName);

            $parameters = SMS::getParameters($this->customer, $templateName);

            if ($template){
                $this->message = SMS::parseTemplate($template->body, $parameters);
            }
        } else {
            $this->validate(['message'=>'required']);
        }

        if (!empty($this->message)) {
            $sms = new SMS($this->message);
            $response = $sms->send($this->customer->phone, true);

            if ($response && $response->status) {
                $this->alert = ['type' => 'success', 'message' => 'Message sent'];
                flash("Message sent successfully.");

                $this->reset(['message']);
                $this->dispatch('sms-sent');

                return;
            }

            //dd($response);

            if ($response->message) {
                flash($response->message, 'info');
            }

            flash("Message sent failed.", 'error');
        }
    }

    public function loadData(Customer $customer): void
    {
        $this->customer = $customer;
        $this->message = null;
        $this->alert = null;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.sms-modal');
    }
}
