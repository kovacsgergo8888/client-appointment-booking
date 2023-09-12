<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use DateTime;
use InvalidArgumentException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');

        if ($from === null || $to === null) {
            throw new InvalidArgumentException('from and to query strings are required');
        }
        $appointments = Appointment::where([
            ['start', '>', $from],
            ['end', '<=', $to],
        ])->get();
        return $appointments->toArray();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
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
}
