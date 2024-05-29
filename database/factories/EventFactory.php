<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class EventFactory extends Factory
{
  /**
   * The current password being used by the factory.
   */
  protected static ?string $password;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $dummyDate = $this->faker->dateTimeThisMonth;
      return [
        'user_id' => 2,
        'title' => $this->faker->word,
        'content' => $this->faker->realText,
        'location' => $this->faker->address,
        'start_at' => $dummyDate->format('Y-m-d H:i:s'),
        'end_at' => $dummyDate->modify('+1hour')->format('Y-m-d H:i:s'),
        'max_people' => $this->faker->numberBetween(1, 20),
        'is_public' => $this->faker->boolean,
        'is_paid' => $this->faker->boolean
      ];
  }

  /**
   * Indicate that the model's email address should be unverified.
   */
  public function unverified(): static
  {
    return $this->state(fn (array $attributes) => [
      'email_verified_at' => null,
    ]);
  }
}
