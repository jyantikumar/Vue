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
        Schema::table('accounts', function (Blueprint $table) {
            $table->integer('parent_id')->default(1);
            $table->integer('account_level')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            if (Schema::hasColumn('accounts', 'parent_id')) {
                $table->dropColumn('parent_id');
            }
            
            if (Schema::hasColumn('accounts', 'account_level')) {
                $table->dropColumn('account_level');
            }
        });
    }
};
