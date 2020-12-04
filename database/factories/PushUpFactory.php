<?php

namespace Database\Factories;

use App\Models\PushUp;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PushUpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PushUp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->createOne()->id,
            'amount' => $this->faker->randomDigitNotNull,
            'points' => $this->faker->randomDigitNotNull
        ];
    }
}
