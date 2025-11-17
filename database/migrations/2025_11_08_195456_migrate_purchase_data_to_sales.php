<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate data from 'purchases' table to 'sales' table
        // DB query to add all in_quantity and out_quantity from purchases to sales using sale_id as reference
        DB::table('sales as st')
            ->join('purchases as pt', 'st.id', '=', 'pt.sale_id')
            ->update([
                'st.in_quantity' => DB::raw('pt.in_quantity'),
                'st.out_quantity' => DB::raw('pt.out_quantity')
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // reverse not possible
    }
};
