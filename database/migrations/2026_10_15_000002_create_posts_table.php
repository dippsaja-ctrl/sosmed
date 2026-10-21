
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type');
            $table->text('caption')->nullable();
            $table->json('yt_meta')->nullable();
            $table->json('media');
            $table->string('mode')->default('now');
            $table->timestamp('scheduled_at')->nullable();
            $table->string('state')->default('draft');

            $table->timestamps();

            $table->index(['state', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
