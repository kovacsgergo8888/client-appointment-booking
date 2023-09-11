<?php

namespace Database\Seeders;

use App\ClientBooking\Repeat;
use App\Models\OpeningHour;
use Illuminate\Database\Seeder;

class OpeningHoursSeeder extends Seeder
{

    private const OPENING_HOURS = [
        [
            'from' => '2023-09-08',
            'to' => null,
            'from_time' => '8:00',
            'to_time' => '10:00',
            'repeat' => Repeat::NO_REPEAT->value,
            'day_of_week' => null,
        ],
        [
            'from' => '2023-01-01',
            'to' => null,
            'from_time' => '10:00',
            'to_time' => '12:00',
            'repeat' => Repeat::EVEN_WEEKS->value,
            'day_of_week' => 1,
        ],
        [
            'from' => '2023-01-01',
            'to' => null,
            'from_time' => '12:00',
            'to_time' => '16:00',
            'repeat' => Repeat::ODD_WEEKS->value,
            'day_of_week' => 3,
        ],
        [
            'from' => '2023-01-01',
            'to' => null,
            'from_time' => '10:00',
            'to_time' => '16:00',
            'repeat' => Repeat::WEEKLY->value,
            'day_of_week' => 5,
        ],
        [
            'from' => '2023-06-01',
            'to' => '2023-11-30',
            'from_time' => '16:00',
            'to_time' => '20:00',
            'repeat' => Repeat::NO_REPEAT->value,
            'day_of_week' => 4,
        ],
    ];
    public function run(): void
    {
        foreach (self::OPENING_HOURS as $anOpeninghHour) {

            $openingHour = new OpeningHour();
            $openingHour->from = $anOpeninghHour['from'];
            $openingHour->to = $anOpeninghHour['to'];
            $openingHour->from_time = $anOpeninghHour['from_time'];
            $openingHour->to_time = $anOpeninghHour['to_time'];
            $openingHour->repeat = $anOpeninghHour['repeat'];
            $openingHour->day_of_week = $anOpeninghHour['day_of_week'];

            $openingHour->save();
        }
    }
}
