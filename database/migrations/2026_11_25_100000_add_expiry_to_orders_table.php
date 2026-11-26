<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('payment_proof');
            $table->enum('status', ['pending', 'waiting_verification', 'paid', 'rejected', 'expired'])
                ->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('expires_at');
            $table->enum('status', ['pending', 'waiting_verification', 'paid', 'rejected'])
                ->default('pending')->change();
        });
    }
};
