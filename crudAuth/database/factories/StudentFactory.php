<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Create 50 faker to fill the student table
     * 
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'nim'  => $this->faker->unique()->numerify('##########'),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'roomId' => $this->faker->numberBetween(1,6),
            'photo' => $this->faker->imageUrl('public/student_photo',640,480, null, false)
        ];
    }
}
