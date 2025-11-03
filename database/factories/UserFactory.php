<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->unique()->name();
        $dom = $this->faker->randomElement(['org', 'net', 'com']);
        $email = Str::slug($name, '.') . '@example.' . $dom;
        /** @var \DateTime $date */
        $date = $this->faker->dateTimeBetween('-40 days');

        return [
            'name' => $name,
            'email' => $email,
            'phone' => preg_replace('/[^0-9]/', '', $this->faker->phoneNumber()),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'created_at' => $date,
            'activity_at' => $this->faker->dateTimeBetween($date),
            'status' => User::STATUS_ACTIVE,
        ];
    }

    public function unverified(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
