<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
<<<<<<< HEAD

    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('type')->default('single');      // single | carousel
        $table->string('title', 100)->nullable();
        $table->text('caption')->nullable();
        $table->string('status')->default('pending');   // pending | published | partial | failed
        $table->timestamps();
    });

    Schema::create('post_media', function (Blueprint $table) {
        $table->id();
        $table->foreignId('post_id')->constrained()->cascadeOnDelete();
        $table->string('disk')->default('public');
        $table->string('path');
        $table->string('mime');
        $table->string('type');                          // image | video
        $table->unsignedBigInteger('size');
        $table->unsignedTinyInteger('position')->default(0);
        $table->timestamps();
    });

    Schema::create('post_deliveries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('post_id')->constrained()->cascadeOnDelete();
        $table->string('platform');                      // youtube | tiktok | instagram
        $table->string('status')->default('pending');    // pending | processing | success | failed
        $table->string('external_id')->nullable();
        $table->text('error')->nullable();
        $table->timestamp('published_at')->nullable();
        $table->timestamps();

        $table->unique(['post_id', 'platform']);
    });

    // add_meta_to_social_accounts_table
    Schema::table('social_accounts', function (Blueprint $table) {
        $table->json('meta')->nullable();   // mis. {"ig_user_id": "1784..."}
    });
        }
=======
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
>>>>>>> bebel

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
