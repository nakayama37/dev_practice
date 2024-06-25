<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class LocationFactory extends Factory
{

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    static $eventId = 1;
      return [
        'event_id' => $eventId++,
        'postcode' => $this->faker->numerify('#######'),
        'venue' => $this->faker->word,
        'prefecture' => $this->faker->city,
        'city' => $this->faker->streetName,
        'street' => $this->faker->streetAddress ];
  }


}
