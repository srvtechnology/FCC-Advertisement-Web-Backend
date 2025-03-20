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
        Schema::create('spaces', function (Blueprint $table) {
            $table->id();
            $table->date('data_collection_date')->nullable();
            $table->string('name_of_person_collection_data')->nullable();
            $table->string('name_of_advertise_agent_company_or_person')->nullable();
            $table->string('name_of_contact_person')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('stree_rd_no')->nullable();
            $table->string('section_of_rd')->nullable();
            $table->string('landmark')->nullable();
            $table->string('gps_cordinate')->nullable();
            $table->text('description_property_advertisement')->nullable();
            $table->string('advertisement_cat_desc')->nullable();
            $table->string('type_of_advertisement')->nullable();
            $table->string('position_of_billboard')->nullable();
            $table->float('lenght_advertise')->nullable();
            $table->float('width_advertise')->nullable();
            $table->float('area_advertise')->nullable();
            $table->string('no_advertisement_sides')->nullable();
            $table->float('clearance_height_advertise')->nullable();
            $table->string('illuminate_nonilluminate')->nullable();
            $table->string('certified_georgia_licensed')->nullable();
            $table->string('landowner_company_corporate')->nullable();
            $table->string('landowner_name')->nullable();
            $table->string('landlord_street_address')->nullable();
            $table->string('landlord_telephone')->nullable();
            $table->string('landlord_email')->nullable();
            $table->string('other_advertisement_sides')->nullable();
            $table->string('rate')->nullable();
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
        Schema::dropIfExists('spaces');
    }
};
