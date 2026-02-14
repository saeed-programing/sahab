<?php

namespace Database\Factories;

use App\Models\Student;
// use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Student::class;
    private static $counterOfNationalCode = 1111111111;
    private static $counterOfStudentCode = 111;
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'family' => fake()->lastName(),
            'class_id' => \App\Models\SchoolClass::inRandomOrder()->first()->id ?? 1,
            'national_code' => self::$counterOfNationalCode++,
            'student_code' => self::$counterOfStudentCode++,
            'image' => 'default.png',
        ];
    }
}
