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
        Schema::create('rfp_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('rfp_number')->unique();
            $table->string('check_voucher_number');
            $table->integer('payee_id');
            $table->enum('request_type', ['Cash Advance','Petty Cash','Credit Card','Reimbursement']);
            $table->enum('status', ['Draft','Pending','Cancelled','Paid']);
            $table->decimal('total_amount', 15, 4);
            $table->timestamps();
        });
        
        Schema::create('liquidation_entries', function (Blueprint $table) {
            $table->id();
            $table->string('rfp_id');
            $table->dateTime('liquidation_date');
            $table->decimal('total_amount', 15, 4);
            $table->timestamps();
        });
        
        Schema::create('liquidation_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('liquidation_id');
            $table->dateTime('tin_number');
            $table->string('payee_name');
            $table->text('address');
            $table->integer('account_id');
            $table->text('particulars');
            $table->decimal('net_amount', 15, 4);
            $table->decimal('vat_exempt_sales', 15, 4);
            $table->decimal('input_vat_non_elite', 15, 4);
            $table->decimal('input_vat_elite', 15, 4);
            $table->decimal('withholding_tax', 15, 4);
            $table->decimal('total_amount', 15, 4);
            $table->timestamps();
        });
        
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->timestamps();
        });
        
        Schema::create('entity_types', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');
            $table->timestamps();
        });
        
        Schema::create('clients_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('entity_type_id');
            $table->string('entity_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfp_transactions');
        Schema::dropIfExists('liquidation_entries');
        Schema::dropIfExists('liquidation_transactions');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('entity_types');
        Schema::dropIfExists('client_subscriptions');
    }
};
