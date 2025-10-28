<?php

namespace App\View\Components\Admin;

use App\Models\Product;
use Illuminate\View\Component;

class ProductForm extends Component
{
    public $product;

    public function render()
    {
        return view('components.admin.product-form', [
            'productTypes' => Product::TYPES
        ]);
    }
}
