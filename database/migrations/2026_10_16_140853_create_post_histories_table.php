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
        Schema::create('post_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_target_id')->constrained('post_targets')->cascadeOnDelete();
            $table->string('platform');
            $table->string('status');                       // success | failed
            $table->unsignedSmallInteger('attempt')->default(1);
            $table->timestamp('published_at')->nullable();  // waktu terbit (hanya jika sukses)
            $table->text('error_message')->nullable();      // hanya jika gagal
            $table->string('post_url', 500)->nullable();    // link postingan asli (hanya jika sukses)
            $table->timestamps();                           // created_at = waktu percobaan

            $table->index(['post_target_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_histories');
    }
};
