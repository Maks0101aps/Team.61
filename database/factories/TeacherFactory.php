<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            'Математика',
            'Українська мова',
            'Українська література',
            'Англійська мова',
            'Фізика',
            'Хімія',
            'Біологія',
            'Історія України',
            'Всесвітня історія',
            'Географія',
            'Інформатика',
            'Фізична культура',
            'Музика',
            'Образотворче мистецтво',
        ];

        $qualifications = [
            'Спеціаліст вищої категорії',
            'Спеціаліст першої категорії',
            'Спеціаліст другої категорії',
            'Спеціаліст',
            'Старший вчитель',
            'Вчитель-методист'
        ];

        return [
            'first_name' => fake('uk_UA')->firstName(),
            'middle_name' => fake('uk_UA')->middleName(),
            'last_name' => fake('uk_UA')->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake('uk_UA')->phoneNumber(),
            'address' => fake('uk_UA')->address(),
            'city' => fake('uk_UA')->city(),
            'region' => fake('uk_UA')->region(),
            'postal_code' => fake('uk_UA')->postcode(),
            'subject' => fake()->randomElement($subjects),
            'qualification' => fake()->randomElement($qualifications),
            'account_id' => null,
        ];
    }
}
