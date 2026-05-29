<?php

namespace Database\Seeders;

use App\Models\DanceClass;
use Illuminate\Database\Seeder;

class SampleDanceClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            ['Monday', '4:00pm - 4:45pm', 'Ballet', '1'],
            ['Tuesday', '4:30pm - 5:15pm', 'Ballet', '1'],
            ['Wednesday', '5:00pm - 6:00pm', 'Ballet', '2'],
            ['Thursday', '5:15pm - 6:15pm', 'Ballet', '2'],
            ['Monday', '5:00pm - 6:00pm', 'Ballet', '3'],
            ['Wednesday', '6:00pm - 7:00pm', 'Ballet', '3'],
            ['Tuesday', '6:00pm - 7:00pm', 'Ballet', '4'],
            ['Thursday', '6:30pm - 7:30pm', 'Ballet', '4'],
            ['Monday', '7:00pm - 8:00pm', 'Ballet', '5'],
            ['Wednesday', '7:00pm - 8:00pm', 'Ballet', '6'],
            ['Monday', '4:45pm - 5:30pm', 'Jazz', '1'],
            ['Tuesday', '5:15pm - 6:00pm', 'Jazz', '1'],
            ['Wednesday', '4:00pm - 5:00pm', 'Jazz', '2'],
            ['Thursday', '4:15pm - 5:15pm', 'Jazz', '2'],
            ['Monday', '6:00pm - 7:00pm', 'Jazz', '3'],
            ['Tuesday', '6:30pm - 7:30pm', 'Jazz', '3'],
            ['Wednesday', '5:00pm - 6:00pm', 'Jazz', '4'],
            ['Thursday', '6:30pm - 7:30pm', 'Jazz', '4'],
            ['Tuesday', '7:30pm - 8:30pm', 'Jazz', '5'],
            ['Thursday', '7:30pm - 8:30pm', 'Jazz', '6'],
            ['Monday', '4:00pm - 4:45pm', 'Tap', '1'],
            ['Wednesday', '4:00pm - 4:45pm', 'Tap', '1'],
            ['Tuesday', '4:45pm - 5:30pm', 'Tap', '2'],
            ['Thursday', '5:15pm - 6:00pm', 'Tap', '2'],
            ['Monday', '7:00pm - 7:45pm', 'Tap', '3'],
            ['Wednesday', '6:00pm - 6:45pm', 'Tap', '3'],
            ['Tuesday', '7:30pm - 8:15pm', 'Tap', '4'],
            ['Thursday', '7:30pm - 8:15pm', 'Tap', '5'],
            ['Friday', '4:00pm - 4:45pm', 'Acro', '1'],
            ['Saturday', '9:00am - 9:45am', 'Acro', '1'],
            ['Friday', '4:45pm - 5:45pm', 'Acro', '2'],
            ['Saturday', '10:00am - 11:00am', 'Acro', '2'],
            ['Friday', '6:00pm - 7:00pm', 'Acro', '3'],
            ['Saturday', '11:00am - 12:00pm', 'Acro', '4'],
            ['Monday', '8:00pm - 8:45pm', 'Pointe', '1'],
            ['Wednesday', '8:00pm - 8:45pm', 'Pointe', '2'],
            ['Thursday', '8:30pm - 9:15pm', 'Pointe', '3'],
            ['Tuesday', '7:45pm - 8:30pm', 'Pointe', 'Pre-Pointe'],
            ['Monday', '5:30pm - 6:15pm', 'Hip Hop', '1st Intermediate'],
            ['Tuesday', '5:30pm - 6:15pm', 'Modern', '1st Intermediate'],
            ['Wednesday', '5:30pm - 6:15pm', 'Lyrical', '1st Intermediate'],
            ['Wednesday', '7:45pm - 8:30pm', 'Hip Hop', 'High Intermediate'],
            ['Monday', '7:15pm - 8:00pm', 'Modern', 'High Intermediate'],
            ['Thursday', '7:45pm - 8:30pm', 'Lyrical', 'High Intermediate'],
            ['Tuesday', '8:30pm - 9:15pm', 'Hip Hop', 'Advanced'],
            ['Wednesday', '8:45pm - 9:30pm', 'Modern', 'Advanced'],
            ['Thursday', '8:45pm - 9:30pm', 'Lyrical', 'Advanced'],
            ['Friday', '5:45pm - 6:30pm', 'Musical Theater', '1'],
            ['Friday', '6:30pm - 7:30pm', 'Musical Theater', '2'],
            ['Friday', '7:30pm - 8:30pm', 'Musical Theater', '3'],
        ];

        foreach ($classes as [$day, $time, $style, $level]) {
            $name = DanceClass::classNameFor($style, $level);

            DanceClass::updateOrCreate(
                [
                    'name' => $name,
                    'day_of_week' => $day,
                    'time' => $time,
                ],
                [
                    'day_of_week' => $day,
                    'time' => $time,
                    'name' => $name,
                    'age_requirement' => null,
                    'dance_style' => $style,
                    'level' => $level,
                ],
            );
        }
    }
}
