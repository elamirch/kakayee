<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'elamir',
        //     'email' => 'amirrezatm@protonmail.com',
        //     'password' => '0bb8577e5e37e06663d5309cab208409',
        // ]);

        //Add majors
        DB::table('majors')->insert([
            'name' => 'Medical Science',
        ]);

        //Add courses
        DB::table('courses')->insert([
            'name' => 'Psychiatry',
            'major_id' => '1',
        ]);
        DB::table('courses')->insert([
            'name' => 'Public Health',
            'major_id' => '1',
        ]);
        DB::table('courses')->insert([
            'name' => 'Surgery',
            'major_id' => '1',
        ]);

        //Add chapters
        for ($j=0; $j < 9; $j++) { 
            DB::table('chapters')->insert([
                'name' => 'Chapter ' . Str::random(10),
                'course_id' => rand(1,3),
           ]);
        }
        
        //Add lessons
        for ($i=0; $i < 81; $i++) { 
            DB::table('lessons')->insert([
                'name' => 'Lesson ' . Str::random(10),
                'chapter_id' => rand(1,9),
                'notes' => fake()->url(),
            ]);        
        }

        //Add cards
        $this->call(CardsSeeder::class);
        
        DB::table('users')->insert([
            'name' => 'elamir',
            'email' => 'amirrezatm@protonmail.com',
            'password' => '$2y$10$5x4.cnGh3M/UKVy3zvnMN.xOpdzIkcJGVKERCvvpunE3y5k7oKb8K',
            'major_id' => 1,
            'progress' => '{}',
            'cycles' => '{}',
            'xp' => '25',
            'heart' => '55',
        ]);
    }
}
