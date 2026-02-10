<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liquidation_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('rfp_id')->change(); 
            $table->foreign('rfp_id')->references('id')->on('rfp_transactions')->onDelete('cascade');
        });

        Schema::table('liquidation_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('liquidation_id')->change();
            $table->unsignedBigInteger('account_id')->change();

            $table->foreign('liquidation_id')->references('id')->on('liquidation_entries')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts');
        });

        Schema::table('clients_subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_type_id')->change();
            $table->foreign('entity_type_id')->references('id')->on('entity_types');
        });
    }

    public function down(): void
    {
        Schema::table('liquidation_entries', function (Blueprint $table) {
            $table->dropForeign(['rfp_id']);
        });

        Schema::table('liquidation_transactions', function (Blueprint $table) {
            $table->dropForeign(['liquidation_id']);
            $table->dropForeign(['account_id']);
        });

        Schema::table('clients_subscriptions', function (Blueprint $table) {
            $table->dropForeign(['entity_type_id']);
        });
    }
};