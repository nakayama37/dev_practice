<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EventFactory extends Factory
{

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $startAt = $this->faker->dateTimeBetween('now', '+1 month');
    $endAt = (clone $startAt)->modify('+' . mt_rand(1, 7) . ' hours');
    
      return [
        'user_id' => 2,
        'title' => $this->faker->word,
        'content' => $this->faker->realText,
        'start_at' => $startAt,
        'end_at' => $endAt,
        'max_people' => 20,
        'price' => 1000,
        'is_public' => $this->faker->boolean,
        'is_paid' => $this->faker->boolean
      ];
  }


}
