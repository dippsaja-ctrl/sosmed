<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('platform');
            $table->string('status')->default('queued'); // queued|sending|retrying|success|failed|canceled
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->string('external_url')->nullable();
            $table->text('note')->nullable();
            $table->text('error')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['post_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_targets');
    }
};
