<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();
        for ($i = 0; $i < 5; $i++) {
            $quiz = Quiz::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
            ]);

            for ($j = 0; $j < 10; $j++) { // 10 câu hỏi cho mỗi quiz
                $question = $quiz->questions()->create([
                    'excerpt' => $faker->sentence,
                    'type' => $faker->randomElement(['checkbox']),
                ]);

                $numAnswers = $question->type === 'text' ? 1 : $faker->numberBetween(2, 4); 
                for ($k = 0; $k < $numAnswers; $k++) {
                    $question->answers()->create([
                        'content' => $faker->sentence,
                        'is_correct' => $k === 0, // Câu trả lời đầu tiên luôn đúng
                    ]);
                }
            }
        }
    }
}
