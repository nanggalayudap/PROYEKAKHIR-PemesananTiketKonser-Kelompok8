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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable()->unique()->after('name');
            $table->string('whatsapp', 25)->nullable()->after('email');
            $table->string('organization_name')->nullable()->after('whatsapp');
            $table->text('office_address')->nullable()->after('organization_name');
            $table->text('eo_description')->nullable()->after('office_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn([
                'username',
                'whatsapp',
                'organization_name',
                'office_address',
                'eo_description',
            ]);
        });
    }
};
