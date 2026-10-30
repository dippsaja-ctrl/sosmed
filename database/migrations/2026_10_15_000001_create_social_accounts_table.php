
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< HEAD
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('platform');               // instagram|facebook|tiktok|youtube
            $table->string('handle')->nullable();     // @username / nama Page / nama channel
            $table->string('external_id')->nullable(); // ig user id, page id, open_id, dst
            $table->text('access_token');             // terenkripsi lewat cast di model
            $table->text('refresh_token')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('connected_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'platform']);
        });
=======
        if (!Schema::hasTable('social_accounts')) {
            Schema::create('social_accounts', function (Blueprint $table) {
                $table->id();

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

                $table->unique(['user_id', 'platform']);
            });
        }
>>>>>>> 66d0ac889e53a75ebc4a07ce64df67b3065f4903
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
