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
        if (! Schema::hasColumn('social_accounts', 'meta')) {
            Schema::table('social_accounts', function (Blueprint $table) {
                $table->json('meta')->nullable()->after('needs_reauth');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('social_accounts', 'meta')) {
            Schema::table('social_accounts', function (Blueprint $table) {
                $table->dropColumn('meta');
            });
        }
    }
};
