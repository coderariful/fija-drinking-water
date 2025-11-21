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

class SellModal extends Component
{
    use SendSmsTrait;

    public ?Customer $customer;
    public ?Product $product;
    public int $product_id;
    public $rate;
    public $quantity = 1;
    public $out_quantity = 0;
    public $paid_amount = 0;
    public $total_amount = 0;
    public $date;
    public $note;

    protected $listeners = [
        'open-modal' => 'loadData'
    ];

    protected $rules = [
        'product_id' => 'required',
        'rate' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
        'out_quantity' => 'required|integer|min:0',
        'total_amount' => 'required',
        'note' => 'nullable',
        'paid_amount'  => 'nullable',
        'date' => 'required|date',
    ];

    public function mount(): void
    {
        $this->product = Product::first();
        $this->product_id = $this->product->id;
        $this->rate = $this->product->price;
        $this->total_amount = floatval($this->rate) * intval($this->quantity);
        $this->date = today()->format('Y-m-d');
    }

    public function loadData(Customer $customer): void
    {
        $this->customer = $customer;
        $this->product = Product::first();
        $this->product_id = $this->product->id;
        $this->rate = $this->customer->jar_rate;
        $this->quantity = 1;
        $this->out_quantity = 0;
        $this->paid_amount = 0;
        $this->total_amount = floatval($this->rate) * intval($this->quantity);
        $this->date = today()->format('Y-m-d');
    }

    public function updatedQuantity(): void
    {
        $this->total_amount = floatval($this->rate) * intval($this->quantity);
    }

    public function updatedRate(): void
    {
        $this->total_amount = floatval($this->rate) * intval($this->quantity);
    }

    public function updatedProductId($value): void
    {
        $this->product = Product::find($value);
        $this->rate = ($this->product->type == 'water') ? $this->customer->jar_rate : $this->product->price;
        $this->total_amount = $this->rate * $this->quantity;
    }

    public function submit(): void
    {
        $data = $this->validate();

        if (!auth_user()->isAdmin()) {
            $this->date = date('Y-m-d');
        }

        Transaction::create([
            'customer_id'  => $this->customer->id,
            'product_id'  => $this->product->id,
            'user_id'      => $this->customer->user_id,
            'product_type' => $this->product->type,
            'in_quantity'  => $this->quantity,
            'out_quantity' => $this->out_quantity,
            'total_amount' => $this->total_amount,
            'paid_amount' => $this->paid_amount,
            'rate'         => $this->rate,
            'created_at' => $this->date,
        ] + $data);

        flash("Transaction added");

        $data = [
            '{sale_count}'  => intval($this->quantity),
            '{sale_rate}'   => intval($this->rate),
            '{bill_amount}' => intval($this->total_amount),
            '{paid_amount}' => intval($this->paid_amount),
            '{due_amount}'  => intval($this->customer->due_amount),
            '{jar_stock}'   => $this->customer->jar_stock,
            '{jar_return}'  => $this->out_quantity,
        ];

        match ($this->product->type) {
            Product::WATER => $this->sendSms($this->customer, $data, 'water-sms'),
            Product::DISPENSER => $this->sendSms($this->customer, $data, 'dispenser-sms')
        };

        $this->reset(['quantity','note','total_amount', 'paid_amount', 'out_quantity' , 'date']);
        $this->total_amount = $this->rate * $this->quantity;

        $this->date = today()->format('Y-m-d');

        $this->dispatch( 'update-customer-index');
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.sell-modal', [
            'products' => Product::oldest('id')->get()
        ]);
    }
}
