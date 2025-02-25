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
        DB::table('users')->insert([
            'name' => 'elamir',
            'email' => 'amirrezatm@protonmail.com',
            'password' => '$2y$10$5x4.cnGh3M/UKVy3zvnMN.xOpdzIkcJGVKERCvvpunE3y5k7oKb8K',
            'major' => 'Medical Science',
            'progress' => '{}',
            'cycles' => '{}',
            'xp' => '25',
            'heart' => '55',
        ]);

        //Add majors
        DB::table('majors')->insert([
            'name' => 'Medical Science',
            'courses' => '[1, 2, 3]',
        ]);

        //Add courses
        DB::table('courses')->insert([
            'name' => 'Psychiatry',
            'chapters' => '[ 1, 2, 3 ]',
        ]);
        DB::table('courses')->insert([
            'name' => 'Public Health',
            'chapters' => '[ 4, 5, 6 ]',
        ]);
        DB::table('courses')->insert([
            'name' => 'Surgery',
            'chapters' => '[ 7, 8, 9 ]',
        ]);

        //Add chapters
        for ($j=0; $j < 9; $j++) { 
            DB::table('chapters')->insert([
                'name' => 'Chapter ' . Str::random(10),
                'lessons' => '[ '. implode(', ' , range($j*9 + 1, $j*9 + 9)) . ' ]',
           ]);
        }
        
        //Add lessons
        for ($i=0; $i < 81; $i++) { 
            DB::table('lessons')->insert([
                'name' => 'Lesson ' . Str::random(10),
                'cards' => '[ ' . implode(', ' , range($i*10 + 1, $i*10 + 10)) . ' ]',
                'notes' => fake()->url(),
            ]);        
        }

        //Add cards
        $this->call(CardsSeeder::class);
    }
}
