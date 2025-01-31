<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EventCategoryFactory extends Factory
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
        'category_id' => $this->faker->numberBetween(1, 5)
      ];
  }


}
