<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class ProductIndex extends Component
{
    public $title = 'All Product';
    public $keyword;

    public function delete(Product $product)
    {
        $product->delete();
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.admin.product-index', [
            'products' => $this->products()
        ])->extends('admin.layouts.master', ['title' => $this->title]);
    }

    public function products(): LengthAwarePaginator|array
    {
        return Product::when($this->keyword, function (Builder $builder, $keyword) {
            $builder->where('name', 'like', "%$keyword%")
                ->orWhere('type', 'like', "%$keyword%")
                ->orWhere('sku', 'like', "%$keyword%");
        })->latest('id')->paginate(RECORDS_PER_PAGE);
    }
}
