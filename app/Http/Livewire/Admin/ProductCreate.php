<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ProductCreate extends Component
{
    public $title = 'Add New Product';

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

    public function submit()
    {
        $data = $this->validate();

        Product::create($data);

        flash('success', 'Product created successfully');

        return redirect()->route('admin.product.index');
    }

    public function render()
    {
        return view('livewire.admin.product-create')->extends('admin.layouts.master', ['title' => $this->title]);
    }
}
