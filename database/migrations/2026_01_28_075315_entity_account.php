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
        // Migration for Entities
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('entity_type'); // Vendor, Client, Subscription, etc.
            $table->string('identifier');  // ID number, Account No, etc.
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            
            // Uniqueness per type and identifier
            $table->unique(['entity_type', 'identifier']); 
            $table->timestamps();
        });

        // Migration for the Binding (Pivot Table)
        Schema::create('account_entity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entity_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            
            // Prevent duplicate entity-account bindings
            $table->unique(['entity_id', 'account_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
