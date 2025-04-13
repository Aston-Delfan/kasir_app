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
        Schema::table('produks', function (Blueprint $table) {
            // Drop foreign key constraint yang ada
            $table->dropForeign('produks_category_id_foreign');

            // Tambahkan kembali foreign key dengan cascade delete
            $table->foreign('category_id')
                  ->references('id')
                  ->on('produks')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // Drop foreign key constraint yang ada
            $table->dropForeign('produks_category_id_foreign');

            // Tambahkan kembali foreign key dengan cascade delete
            $table->foreign('category_id')
                  ->references('id')
                  ->on('produks');
        });
    }
};
