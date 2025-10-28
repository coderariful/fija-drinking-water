<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ProductEdit extends Component
{
    public $title = 'Edit Product';

    public Product $product;

    public $type;
    public $name;
    public $sku;
    public $price;

    protected $rules = [
        "type" => "required",
        "name" => "required",
        "sku" => "nullable",
        "price" => "required",
    ];

    public function mount()
    {
        $this->fill($this->product);
    }

    public function submit()
    {
        $data = $this->validate();

        $this->product->update($data);

        flash('success', 'Product update successfully');

        return redirect()->route('admin.product.index');
    }

    public function render()
    {
        return view('livewire.admin.product-edit')->extends('admin.layouts.master', ['title' => $this->title]);
    }
}
