<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentCollectionRequest;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\OpeningHour;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\ClientBooking\Repeat;
use Illuminate\Contracts\Database\Eloquent\Builder;
use DateTime;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AppointmentCollectionRequest $request)
    {
        return [
            ...$this->appointments($request->get('from'), $request->get('to')),
            ...$this->fixOpeningHours($request->get('from'), $request->get('to')),
            ...$this->repeatingOpeningHours($request->get('from'), $request->get('to'))
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $appointment = new Appointment();
        $appointment->client_name = $request->get('clientName');
        $appointment->start = $request->date('start');
        $appointment->end = $request->date('end');

        if ($appointment->ableToBook()) {
            $appointment->save();
            return $appointment->toArray();
        }

        return new JsonResponse(['message' => 'Sikertelen hozzáadás'], 422);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }

    private function appointments(string $from, string $to): array
    {
        $appointments = Appointment::where([
            ['start', '>=', $from],
            ['end', '<=', $to],
        ])->get();

        return array_map(
            fn ($appointment) => [
                'title' => $appointment['client_name'],
                'start' => $appointment['start'],
                'end' => $appointment['end'],
            ],
            $appointments->toArray()
        );
    }

    private function fixOpeningHours(string $from, string $to): array
    {
        $fixOpeningHours = OpeningHour::where([
            ['repeat', '=', Repeat::NO_REPEAT->value],
            ['from', '>=', $from],
            ['from', '<=', $to]
        ])->get();
        return array_map(
            fn ($openingHour) => [
                'title' => 'Foglalasi ido',
                'display' => 'background',
                'start' => "{$openingHour['from']} {$openingHour['from_time']}",
                'end' => "{$openingHour['from']} {$openingHour['to_time']}",

            ],
            $fixOpeningHours->toArray()
        );
    }

    private function repeatingOpeningHours(string $from, string $to): array
    {
        $day = [
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            7 => 'sunday'
        ];
        $fromTime = new DateTime($from);
        $showingWeekType = (int)$fromTime->format('W') % 2
            ? Repeat::ODD_WEEKS
            : Repeat::EVEN_WEEKS;

        $repeatingOpeningHours = [];
        $openinghHours = OpeningHour::where([
            ['repeat', '!=', Repeat::NO_REPEAT],
            ['from', '<=', $to],
        ])
            ->where(function (Builder $query) use ($from) {
                $query->whereNull('to')
                    ->orWhere('to', '>=', $from);
            })
            ->get();

        foreach ($openinghHours as $openingHour) {
            if (
                Repeat::from($openingHour->repeat) !== Repeat::WEEKLY
                && Repeat::from($openingHour->repeat) !== $showingWeekType
            ) {
                continue;
            }

            $start = new DateTime($from);
            $start->modify("{$day[$openingHour['day_of_week']]} {$openingHour['from_time']}");

            $end = clone $start;
            $end->modify("{$openingHour['to_time']}");

            $repeatingOpeningHours[] = [
                'title' => 'Foglalasi ido',
                'start' => $start->format('Y-m-d H:i:s'),
                'end' => $end->format('Y-m-d H:i:s'),
                'display' => 'background',
            ];
        }

        return $repeatingOpeningHours;
    }
}
