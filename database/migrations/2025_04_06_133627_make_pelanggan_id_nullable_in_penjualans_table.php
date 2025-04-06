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
        Schema::table('penjualans', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['pelanggan_id']);

            // Change the column to be nullable
            $table->foreignId('pelanggan_id')->nullable()->change();

            // Add the foreign key constraint but allow null values
            $table->foreign('pelanggan_id')
                  ->references('id')
                  ->on('pelanggans')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            // Drop the modified foreign key constraint
            $table->dropForeign(['pelanggan_id']);

            // Change back to non-nullable and add the original constraint
            $table->foreignId('pelanggan_id')->nullable(false)->change();
            $table->foreign('pelanggan_id')
                  ->references('id')
                  ->on('pelanggans');
        });
    }
};
