
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
