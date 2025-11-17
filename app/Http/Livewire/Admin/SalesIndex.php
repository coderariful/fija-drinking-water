<?php

namespace App\Http\Livewire\Admin;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class SalesIndex extends Component
{
    use WithPagination;

    public $title = 'Sales';
    public $employee_id;
    public $customer_id;
    public $product_id;
    public $keyword;
    public $only_date;
    public $start_date;
    public $end_date;
    public $type = 'day';

    public $quantity = [];
    public $date_created = [];

    public function mount()
    {
        if (request('product')) {
            if (request('product') == 'jar') {
                $product = Product::where('type', PRODUCT_WATER)->first();
                $this->product_id = $product->id;
            }
            if (request('product') !== 'jar') {
                $this->product_id = request('product');
            }
        }
        $dates = request('dates',['start' => null, 'end' => null]);
        if ($dates['start'] || $dates['end']) {
            $this->type = 'range';
            $this->start_date = $dates['start'];
            $this->end_date = $dates['end'];
        }
        if (request('range')) {
            $this->type = 'range';
            if (request('range') == 'month') {
                $this->start_date = today()->firstOfMonth()->format('Y-m-d');
                $this->end_date = today()->lastOfMonth()->format('Y-m-d');
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

    public function deleteSale(Transaction $sale)
    {
        try {
            $sale->purchase?->payment?->delete();
            $sale->purchase?->delete();
            $sale->delete();

            flash(trans('Sale entry deleted!'));
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveQtyUpdate(Transaction $sale)
    {
        try {
            $sale->quantity = $this->quantity[$sale->id];
            $sale->total_cost = $sale->rate * $sale->quantity;
            $sale->save();

            $purchase = $sale->purchase;
            $purchase->in_quantity = $sale->quantity;
            $purchase->save();

            flash(trans('Sale quantity entry updated!'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function saveDateUpdate(Transaction $sale)
    {
        try {
            $sale->created_at = $this->date_created[$sale->id];
            $sale->save();

            $purchase = $sale->purchase;
            $purchase->created_at = $sale->created_at;
            $purchase->save();

            if ($purchase->payment) {
                $payment = $purchase->payment;
                $payment->created_at = $sale->created_at;
                $payment->save();
            }

            flash(trans('Sale date updated!'));

            $this->dispatch('entryUpdated');
        } catch (Exception $e) {
            toastr($e->getMessage() . "<br>Please try again!", 'error', 'Server Error');
        }
    }

    public function render(): Factory|View|Application
    {
        $sales = Transaction::query()
            ->with(['customer', 'product', 'user'])
            ->where('product_type', PRODUCT_WATER)
            ->when($this->employee_id, fn($query, $employee_id) => $query->where('user_id', $employee_id))
            ->when($this->customer_id, fn($query, $customer_id) => $query->where('customer_id', $customer_id))
            ->when($this->product_id, fn($query, $product_id) => $query->where('product_id', $product_id))
            ->when($this->keyword, function(Builder $builder, $keyword) {
                $builder->whereRelation('user', 'name', 'like', "%$keyword%")
                    ->orWhereRelation('customer', 'name', 'like', "%$keyword%")
                    ->orWhereRelation('customer', 'phone', 'like', "%$keyword%")
                    ->orWhereRelation('product', 'name', 'like', "%$keyword%")
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
            ->paginate(20);

        foreach ($sales as $sale) {
            $this->quantity[$sale->id] = $sale->quantity;
            $this->date_created[$sale->id] = $sale->created_at?->format('Y-m-d');
        }

        return view('livewire.admin.sales-index', [
            'sales' => $sales,
            'employees' => User::all(),
            'customers' => Customer::when($this->employee_id, fn($query, $employee_id) => $query->where('user_id', $employee_id))->get(),
            'products' => Product::all(),
        ])->extends('admin.layouts.master', ['title' => $this->title]);
    }
}
