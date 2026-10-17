<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<< HEAD:database/migrations/2026_10_15_000001_create_social_accounts_table.php
return new class extends Migration {
=======
return new class extends Migration
{
>>>>>>> bebel:database/migrations/2026_09_28_101743_create_social_accounts_table.php
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
<<<<<<< HEAD:database/migrations/2026_10_15_000001_create_social_accounts_table.php
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('platform');               // instagram|facebook|tiktok|youtube
            $table->string('handle')->nullable();     // @username / nama Page / nama channel
            $table->string('external_id')->nullable(); // ig user id, page id, open_id, dst
            $table->text('access_token');             // terenkripsi lewat cast di model
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamps();
=======

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('platform');
            $table->string('handle')->nullable();
            $table->string('external_id')->nullable();
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('connected_at')->nullable();

            $table->timestamps();

>>>>>>> bebel:database/migrations/2026_09_28_101743_create_social_accounts_table.php
            $table->unique(['user_id', 'platform']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};