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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type');                     // video|carousel|photo
            $table->text('caption')->nullable();
            $table->json('yt_meta')->nullable();        // title, desc, tags, category, visibility
            $table->json('media');                      // [{path,name,kind,size,width,height,duration,mime}]
            $table->string('mode')->default('now');     // now|schedule
            $table->timestamp('scheduled_at')->nullable();
            $table->string('state')->default('draft');  // draft|scheduled|processing|done|canceled
            $table->timestamps();
            $table->index(['state', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
