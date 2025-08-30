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
            $table->string('company')->nullable()->after('email');
            $table->string('phone')->nullable()->after('company');
            $table->enum('tier', ['user', 'paid', 'admin'])->default('user')->after('phone');
            $table->string('subscription_status')->default('inactive')->after('tier');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company', 'phone', 'tier', 'subscription_status', 'subscription_expires_at']);
        });
    }
};
