<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\Source;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $medical = Major::create(['name' => ['en' => 'Medical Sciences', 'fa' => 'علوم پزشکی']]);
        $nursing = Major::create(['name' => ['en' => 'Nursing', 'fa' => 'پرستاری']]);
        $pharmacy = Major::create(['name' => ['en' => 'Pharmacy', 'fa' => 'داروسازی']]);

        $source = Source::create(['source' => ['en' => 'Harrison\'s Principles of Internal Medicine', 'fa' => 'هاریسون اصول طب داخلی']]);

        $courses = [
            [$medical, 'Anatomy', 'آناتومی'],
            [$medical, 'Physiology', 'فیزیولوژی'],
            [$medical, 'Pathology', 'پاتولوژی'],
            [$medical, 'Pharmacology', 'فارماکولوژی'],
            [$medical, 'Psychiatry', 'روان‌پزشکی'],
            [$nursing, 'Fundamentals of Nursing', 'اصول پرستاری'],
            [$nursing, 'Internal-Surgical Nursing', 'پرستاری داخلی-جراحی'],
            [$pharmacy, 'Pharmaceutical Chemistry', 'شیمی دارویی'],
            [$pharmacy, 'Pharmacotherapy', 'درمان دارویی'],
        ];

        $lessonTemplates = ['Introduction', 'Basics', 'Advanced Topics', 'Clinical Cases', 'Review'];

        $order = 0;
        foreach ($courses as [$major, $enName, $faName]) {
            $course = Course::create([
                'name' => ['en' => $enName, 'fa' => $faName],
                'major_id' => $major->id,
            ]);

            foreach ($lessonTemplates as $i => $template) {
                $lesson = Lesson::create([
                    'name' => [
                        'en' => "{$template}: {$enName}",
                        'fa' => "{$template}: {$faName}",
                    ],
                    'notes' => [
                        'en' => "Study notes for {$template} of {$enName}.",
                        'fa' => "یادداشت‌های مطالعه برای {$template} درس {$faName}.",
                    ],
                    'course_id' => $course->id,
                ]);

                Card::factory()->count(6)->create([
                    'lesson_id' => $lesson->id,
                    'source_id' => $source->id,
                    'order' => $order,
                ]);
            }
        }
    }
}
