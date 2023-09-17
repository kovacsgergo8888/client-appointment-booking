<?php

namespace App\ClientBooking;

use App\Models\Appointment;
use App\Models\OpeningHour;
use DateTimeImmutable;

class AppointmentOpeningHourValidator
{
    private DateTimeImmutable $openingHourFrom;
    private DateTimeImmutable $openingHourTo;
    private DateTimeImmutable $appointmentStart;
    private DateTimeImmutable $appointmentEnd;

    private ?int $dayOfWeek;
    private Repeat $repeat;

    public function validate(Appointment $appointment, OpeningHour $openingHour): bool
    {
        $this->appointmentStart = new DateTimeImmutable($appointment->start);
        $this->appointmentEnd = new DateTimeImmutable($appointment->end);

        $this->openingHourFrom = new DateTimeImmutable("{$openingHour->from} {$openingHour->from_time}");
        $this->setOpeningHourTo($appointment, $openingHour);

        $this->repeat = Repeat::from($openingHour->repeat);
        $this->dayOfWeek = $openingHour->day_of_week;

        return match ($this->repeat) {
            Repeat::NO_REPEAT => $this->validateNoRepeat(),
            Repeat::WEEKLY => $this->validateWeekly(),
            Repeat::ODD_WEEKS, Repeat::EVEN_WEEKS => $this->validateEvenOddWeekly(),
            default => false
        };
    }

    private function setOpeningHourTo(Appointment $appointment, OpeningHour $openingHour): void
    {
        if ($openingHour->repeat === Repeat::NO_REPEAT->value) {
            $this->openingHourTo = new DateTimeImmutable("{$openingHour->from} {$openingHour->to_time}");
            return;
        }
        $this->openingHourTo = $openingHour->to !== null
            ? new DateTimeImmutable("{$openingHour->to} {$openingHour->to_time}")
            : new DateTimeImmutable("{$this->appointmentEnd->format('Y-m-d')} {$openingHour->to_time}");
    }

    private function validateNoRepeat(): bool
    {
        return
            $this->appointmentStart >= $this->openingHourFrom
            && $this->appointmentStart <= $this->openingHourTo
            && $this->appointmentEnd >= $this->openingHourFrom
            && $this->appointmentEnd <= $this->openingHourTo;
    }

    private function validateTime(): bool
    {
        $openingFrom = $this->appointmentStart->setTime(
            (int)$this->openingHourFrom->format('H'),
            (int)$this->openingHourFrom->format('i')
        );

        $openingTo = $this->appointmentEnd->setTime(
            (int)$this->openingHourTo->format('H'),
            (int)$this->openingHourTo->format('i')
        );

        return $this->appointmentStart >= $openingFrom
            && $this->appointmentEnd <= $openingTo;
    }

    private function validateWeekly(): bool
    {
        $appointmentGreaterThanOpeningDate = $this->appointmentStart >= $this->openingHourFrom;
        $appointmentLessThanOpeningDate =  $this->appointmentEnd <= $this->openingHourTo;

        return
            $appointmentGreaterThanOpeningDate
            && $appointmentLessThanOpeningDate
            && $this->validateTime()
            && (int)$this->appointmentStart->format('N') === (int)$this->dayOfWeek;
    }

    private function validateEvenOddWeekly(): bool
    {
        $appointmentWeekNumber = (int)$this->appointmentStart->format('W');
        return
            $this->validateWeekly()
            && $appointmentWeekNumber % 2 === ($this->repeat === Repeat::EVEN_WEEKS ? 0 : 1);
    }
}
