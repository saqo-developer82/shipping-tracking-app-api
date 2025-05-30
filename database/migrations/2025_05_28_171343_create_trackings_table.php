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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->date('estimated_delivery_date');
            $table->string('status');
            $table->string('carrier');
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->timestamps();

            $table->index('tracking_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
