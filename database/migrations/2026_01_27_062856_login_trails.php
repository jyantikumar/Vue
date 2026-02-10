<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('failed_attempts')->default(0);
            $table->boolean('is_locked')->default(false);
        });

        Schema::create('authentication_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('event_type');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'failed_attempts')) {
                $table->dropColumn('failed_attempts');
            }
            
            if (Schema::hasColumn('users', 'is_locked')) {
                $table->dropColumn('is_locked');
            }
        });
        Schema::dropIfExists('authentication_logs');
    }
};
