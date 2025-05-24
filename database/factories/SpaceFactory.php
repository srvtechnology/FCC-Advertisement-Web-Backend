<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = \App\Models\Space::class;

    public function definition()
    {
        return [
            'space_cat_id' => $this->faker->numberBetween(1, 4),
            'data_collection_date' => $this->faker->date(),
            'name_of_person_collection_data' => $this->faker->name(),
            'name_of_advertise_agent_company_or_person' => $this->faker->company(),
            'name_of_contact_person' => $this->faker->name(),
            'telephone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
            'location' => $this->faker->address(),
            'stree_rd_no' => $this->faker->randomDigit(),
            'section_of_rd' => $this->faker->randomDigit(),
            'landmark' => $this->faker->streetName(),
            'gps_cordinate' => $this->faker->latitude() . ', ' . $this->faker->longitude(),
            'description_property_advertisement' => $this->faker->text(50),
            'advertisement_cat_desc' => $this->faker->word(),
            'type_of_advertisement' => $this->faker->randomElement(['Billboard', 'Poster', 'Digital Screen']),
            'position_of_billboard' => $this->faker->randomElement(['Left', 'Right', 'Top']),
            'lenght_advertise' => $this->faker->randomFloat(2, 5, 50),
            'width_advertise' => $this->faker->randomFloat(2, 5, 50),
            'area_advertise' => $this->faker->randomFloat(2, 10, 200),
            'no_advertisement_sides' => $this->faker->numberBetween(1, 4),
            'clearance_height_advertise' => $this->faker->randomFloat(2, 5, 20),
            'illuminate_nonilluminate' => $this->faker->randomElement(['Illuminate', 'Non-Illuminate']),
            'certified_georgia_licensed' => $this->faker->boolean(),
            'landowner_company_corporate' => $this->faker->company(),
            'landowner_name' => $this->faker->name(),
            'landlord_street_address' => $this->faker->address(),
            'landlord_telephone' => $this->faker->phoneNumber(),
            'landlord_email' => $this->faker->email(),
        ];
    }
}
