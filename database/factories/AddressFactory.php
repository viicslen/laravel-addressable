<?php

namespace ViicSlen\Addressable\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ViicSlen\Addressable\Models\Address;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'company' => $this->faker->company(),
            'street1' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country_code' => $this->faker->countryCode(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
