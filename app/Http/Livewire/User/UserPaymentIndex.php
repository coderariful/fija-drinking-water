<?php

namespace App\Http\Livewire\User;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class UserPaymentIndex extends Component
{
    use WithPagination;

    public $title = 'Payments';
    public $employee_id;
    public $customer_id;
    public $product_id;
    public $keyword;
    public $only_date;
    public $start_date;
    public $end_date;

    public function mount()
    {
        if (request('product')) {
            if (request('product') == 'jar') {
                $product = Product::where('type', PRODUCT_WATER)->first();
                $this->product_id = $product->id;
            }
        }

        $days = [
            'today'=> date('Y-m-d'),
        ];
        if (request('day')) {
            $this->only_date = $days[request('day')] ?? null;
        }
    }

    public function updatedOnlyDate()
    {
        $this->start_date = null;
        $this->end_date = null;
    }

    public function updatedStartDate()
    {
        $this->only_date = null;
    }

    public function updatedEndDate()
    {
        $this->only_date = null;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.user.user-payment-index', [
            'sales' => Transaction::query()
                ->with(['customer', 'user'])
                ->where('user_id', auth()->id())
                ->when($this->customer_id, fn($query, $customer_id) => $query->where('customer_id', $customer_id))
                ->when($this->keyword, function(Builder $builder, $keyword) {
                    $builder->whereRelation('user', 'name', 'like', "%$keyword%")
                        ->orWhereRelation('customer', 'name', 'like', "%$keyword%")
                        ->orWhereRelation('customer', 'phone', 'like', "%$keyword%")
                        ->orWhere('note', 'like', "%$keyword%");
                })
                ->when($this->only_date, function (Builder $builder, $only_date) {
                    $builder->whereDate('created_at', '=', $only_date);
                })
                ->when($this->start_date, function (Builder $builder, $start_date) {
                    $builder->whereDate('created_at', '>=', $start_date);
                })
                ->when($this->end_date, function (Builder $builder, $end_date) {
                    $builder->whereDate('created_at', '<=', $end_date);
                })
                ->paginate(10),
            'employees' => User::all(),
            'customers' => Customer::when($this->employee_id, fn($query, $employee_id) => $query->where('user_id', $employee_id))->get(),
            'products' => Product::all(),
        ])->extends('user.layouts.master', ['title' => $this->title]);
    }
}
