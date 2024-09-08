<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\Quiz;
use App\Models\QuizCode;
use App\Models\Topic;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        DB::table("users")->insert([
            'name' => 'Dilan',
            'email' => 'zild.fyu@gmail.com',
            'password' => Hash::make('pyopyo123'),
        ]);


        Lesson::factory()->count(3)->create()->each(function ($lesson) {
            Topic::factory()->count(20)->create(['lesson_id' => $lesson->id])->each(
                function ($topic) {
                    Quiz::factory()->count(3)->create(['topic_id' => $topic->id])->each(function ($quiz) {
                        QuizCode::factory()->count(1)->create(['quiz_id' => $quiz->id]);
                        Question::factory()->count(10)->create(['quiz_id' => $quiz->id])->each(function ($question) {
                            QuestionChoice::factory()->count(4)->create(['question_id'=> $question->id]);
                        });
                    });
                }
            );
        });
    }
}
