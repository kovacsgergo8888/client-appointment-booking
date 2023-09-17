<?php

namespace Tests\Unit\ClientBooking;

use App\ClientBooking\AppointmentOpeningHourValidator;
use App\ClientBooking\Repeat;
use App\Models\Appointment;
use App\Models\OpeningHour;
use PHPUnit\Framework\TestCase;

class AppointmentOpeningHourValidatorTest extends TestCase
{
    /**
     *
     * @dataProvider cases
     */
    public function testValidate(
        bool $expected,
        string $appointmentStart,
        string $appointmentEnd,
        string $openingHourFrom,
        ?string $openingHourTo,
        string $openingHourFromTime,
        string $openingHourToTime,
        string $repeat,
        ?int $dayOfWeek,
    ): void {
        $validator = new AppointmentOpeningHourValidator();

        $appointment = new Appointment();
        $appointment->start = $appointmentStart;
        $appointment->end = $appointmentEnd;

        $openingHour = new OpeningHour();
        $openingHour->from = $openingHourFrom;
        $openingHour->to = $openingHourTo;
        $openingHour->from_time = $openingHourFromTime;
        $openingHour->to_time = $openingHourToTime;
        $openingHour->repeat = $repeat;
        $openingHour->day_of_week = $dayOfWeek;

        $isValid = $validator->validate($appointment, $openingHour);
        $this->assertEquals($expected, $isValid);
    }

    public static function cases(): array
    {
        return [
            ///// no repeat
            [
                true,
                '2023-10-10 10:00',
                '2023-10-10 11:00',
                '2023-10-10',
                '2023-10-10',
                '10:00',
                '11:00',
                Repeat::NO_REPEAT->value,
                null,
            ],
            [
                false,
                '2023-10-10 10:00',
                '2023-10-10 12:00',
                '2023-10-10',
                '2023-10-10',
                '10:00',
                '11:00',
                Repeat::NO_REPEAT->value,
                null,
            ],
            [
                false,
                '2023-10-10 09:59',
                '2023-10-10 11:00',
                '2023-10-10',
                '2023-10-10',
                '10:00',
                '11:00',
                Repeat::NO_REPEAT->value,
                null,
            ],

            ///// every week
            [
                true,
                '2023-10-10 10:00',
                '2023-10-10 11:00',
                '2023-10-03',
                '2023-10-10',
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                2,
            ],
            [
                true,
                '2023-10-09 10:00',
                '2023-10-09 11:00',
                '2023-10-01',
                '2023-10-31',
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                1,
            ],
            [
                true,
                '2023-10-09 10:00',
                '2023-10-09 11:00',
                '2023-10-01',
                null,
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                1,
            ],
            [
                false,
                '2023-10-09 10:00',
                '2023-10-09 11:00',
                '2023-10-10',
                null,
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                1,
            ],
            [
                false,
                '2023-10-10 10:00',
                '2023-10-10 11:00',
                '2023-09-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                2,
            ],
            [
                false,
                '2023-10-10 09:00',
                '2023-10-10 11:00',
                '2023-09-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                2,
            ],
            [
                false,
                '2023-10-10 10:00',
                '2023-10-10 12:00',
                '2023-09-03',
                '2023-12-10',
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                2,
            ],
            [
                false,
                '2023-10-10 10:00',
                '2023-10-10 12:00',
                '2023-09-03',
                null,
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                2,
            ],
            [
                false,
                '2023-10-10 09:59',
                '2023-10-10 11:00',
                '2023-09-03',
                null,
                '10:00',
                '11:00',
                Repeat::WEEKLY->value,
                2,
            ],

            ///// EVEN weeks
            [
                false,
                '2023-01-16 10:00',
                '2023-01-16 11:00',
                '2023-01-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::EVEN_WEEKS->value,
                1,
            ],
            [
                false,
                '2023-01-10 10:00',
                '2023-01-10 11:00',
                '2023-01-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::EVEN_WEEKS->value,
                1,
            ],
            [
                true,
                '2023-01-09 10:00',
                '2023-01-09 11:00',
                '2023-01-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::EVEN_WEEKS->value,
                1,
            ],
            [
                false,
                '2023-01-09 10:00',
                '2023-01-09 11:01',
                '2023-01-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::EVEN_WEEKS->value,
                1,
            ],

            /// ODD weeks

            [
                false,
                '2023-01-09 10:00',
                '2023-01-09 11:00',
                '2023-01-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::ODD_WEEKS->value,
                1,
            ],
            [
                true,
                '2023-01-16 10:00',
                '2023-01-16 11:00',
                '2023-01-03',
                '2023-09-10',
                '10:00',
                '11:00',
                Repeat::ODD_WEEKS->value,
                1,
            ],
        ];
    }
}
