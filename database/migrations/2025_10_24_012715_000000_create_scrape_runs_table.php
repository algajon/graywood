<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('scrape_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();              // run_id from scraper
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('base_url');
            $table->unsignedInteger('max_listings')->nullable();
            $table->string('status')->default('queued'); // queued|running|succeeded|failed
            $table->unsignedInteger('count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('downloaded_at')->nullable(); // when user downloaded CSV
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('scrape_runs');
    }
};
