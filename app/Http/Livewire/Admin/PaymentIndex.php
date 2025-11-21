<?php

namespace App\Http\Livewire\Admin;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentIndex extends Component
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

    public $paid_amount = [];
    public $date_created = [];

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

    public function deletePayment(Transaction $payment): void
    {
        try {
            $payment->delete();

            flash(trans("Payment entry deleted."), "success");
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveAmountUpdate(Transaction $payment): void
    {
        try {
            $payment->paid_amount = $this->paid_amount[$payment->id];
            $payment->save();

            flash(trans('Sale quantity entry updated!'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveDateUpdate(Transaction $payment): void
    {
        try {
            $payment->created_at = $this->date_created[$payment->id];
            $payment->save();

            if ($payment->purchase) {
                $purchase = $payment->purchase;
                $purchase->created_at = $payment->created_at;
                $purchase->save();

                if ($purchase->sale) {
                    $sale = $purchase->sale;
                    $sale->created_at = $payment->created_at;
                    $sale->save();
                }
            }

            flash(trans('Payment date updated!'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }


    public function updatedOnlyDate(): void
    {
        $this->start_date = null;
        $this->end_date = null;
    }

    public function updatedStartDate(): void
    {
        $this->only_date = null;
    }

    public function updatedEndDate(): void
    {
        $this->only_date = null;
    }

    public function render(): Factory|View|Application
    {
        $payments = Transaction::query()
            ->notObsulate()
            ->with(['customer', 'user'])
            ->where('paid_amount', '>', 0)
            ->when($this->product_id, fn($query, $product_id) => $query->where('product_id', $product_id))
            ->when($this->employee_id, fn($query, $employee_id) => $query->where('user_id', $employee_id))
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
            ->latest('created_at')
            ->latest('id')
            ->paginate(RECORDS_PER_PAGE);

        foreach ($payments as $sale) {
            $this->paid_amount[$sale->id] = $sale->paid_amount;
            $this->date_created[$sale->id] = $sale->created_at?->format('Y-m-d');
        }

        return view('livewire.admin.payment-index', [
            'sales' => $payments,
            'employees' => User::all(),
            'customers' => Customer::when($this->employee_id, fn($query, $employee_id) => $query->where('user_id', $employee_id))->get(),
            'products' => Product::all(),
        ])->extends('admin.layouts.master', ['title' => $this->title]);
    }
}
