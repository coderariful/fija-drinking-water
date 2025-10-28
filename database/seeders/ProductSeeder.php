<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Product::create([
            'name'=> 'One Tap Dispenser',
            'type'=> Product::DISPENSER,
            'sku'=> 'DOT',
            'price'=> 150.00
        ]);
        Product::create([
            'name'=> 'Two Tap Dispenser',
            'type'=> Product::DISPENSER,
            'sku'=> 'DTT',
            'price'=> 250.00
        ]);
    }
}
