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
        // 1. Add image_path to properties
        Schema::table('properties', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('address');
        });

        // 2. Create property_units table
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name'); // e.g. "Shop 1 - Mobile", "1st Floor Back Portion"
            $table->string('type')->default('portion'); // portion, shop, flat, floor, room, other
            $table->decimal('default_rent', 15, 2)->default(0);
            $table->string('status')->default('vacant'); // vacant, occupied
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 3. Add unit_id to leases
        Schema::table('leases', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->after('property_id')->constrained('property_units')->onDelete('set null');
        });

        // 4. Enhance invoices with arrears, utilities, and security breakdown
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('base_rent', 15, 2)->default(0)->after('lease_id');
            $table->decimal('arrears', 15, 2)->default(0)->after('base_rent');
            $table->decimal('utility_charges', 15, 2)->default(0)->after('arrears');
            $table->string('utility_notes')->nullable()->after('utility_charges');
            $table->decimal('security_due', 15, 2)->default(0)->after('utility_notes');
            $table->decimal('security_paid', 15, 2)->default(0)->after('security_due');
        });

        // 5. Enhance payments with split rent & security tracking
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('rent_amount', 15, 2)->default(0)->after('invoice_id');
            $table->decimal('security_amount', 15, 2)->default(0)->after('rent_amount');
            $table->text('notes')->nullable()->after('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['rent_amount', 'security_amount', 'notes']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['base_rent', 'arrears', 'utility_charges', 'utility_notes', 'security_due', 'security_paid']);
        });

        Schema::table('leases', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::dropIfExists('property_units');

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};

