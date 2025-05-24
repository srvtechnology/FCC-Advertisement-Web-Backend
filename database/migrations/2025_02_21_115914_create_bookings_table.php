<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who booked
            $table->foreignId('space_id')->constrained()->onDelete('cascade'); // Space being booked
            $table->date('start_date'); // Booking start date
            $table->string('period'); // Booking start date
            $table->date('end_date'); // Booking end date
            $table->string('customer_name'); 
            $table->string('customer_email'); 
            $table->string('mobile'); 
            $table->string('address'); 
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
