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
        Schema::table('detail_pembelians', function (Blueprint $table) {
            // Drop foreign key constraint yang ada
            $table->dropForeign('detail_pembelians_pembelian_id_foreign');

            // Tambahkan kembali foreign key dengan cascade delete
            $table->foreign('pembelian_id')
                  ->references('id')
                  ->on('pembelians')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pembelians', function (Blueprint $table) {
            // Drop foreign key dengan cascade
            $table->dropForeign('detail_pembelians_pembelian_id_foreign');

            // Kembalikan foreign key tanpa cascade
            $table->foreign('pembelian_id')
                  ->references('id')
                  ->on('pembelians');
        });
    }
};
