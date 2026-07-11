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
            $table->string('phone_number')->nullable()->after('password');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_number');
            $table->boolean('two_factor_enabled')->default(false)->after('phone_verified_at');
            $table->string('two_factor_preferred_channel')->nullable()->after('two_factor_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'two_factor_preferred_channel',
                'two_factor_enabled',
                'phone_verified_at',
                'phone_number',
            ]);
        });
    }
};
