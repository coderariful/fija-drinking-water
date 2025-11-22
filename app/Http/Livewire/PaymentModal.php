<?php

namespace App\Http\Livewire;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Traits\SendSmsTrait;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PaymentModal extends Component
{
    use SendSmsTrait;

    public ?Customer $customer;

    public $pay_amount = 0;
    public $note;
    public $date;

    protected $listeners = [
        'open-modal' => 'loadData'
    ];

    public function mount()
    {
        $this->date = today()->format('Y-m-d');
    }

    public function loadData(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function submit()
    {
        $this->validate(['pay_amount' => 'required|numeric|min:10']);

        $data = [
            'customer_id' => $this->customer->id,
            'user_id'     => auth()->user()->id,
            'paid_amount' => $this->pay_amount,
            'note' => $this->note,
            'created_at' => $this->date,
        ];

        if (auth_user()->user_type!=USER_ADMIN) {
            $data['created_at'] = date('Y-m-d');
        }

        $payment = Transaction::create($data);

        flash("Payment saved");

        $data = [
            '{paid_amount}' => intval($this->pay_amount),
            '{due_amount}' => intval($this->customer->due_amount),
        ];

        $this->sendSms($this->customer, $data, 'payment-sms');


        $this->reset(['note', 'pay_amount', 'date']);

        $this->date = today()->format('Y-m-d');

        $this->dispatch('update-customer-index');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.payment-modal');
    }
}
