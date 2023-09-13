<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentCollectionRequest;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AppointmentCollectionRequest $request)
    {
        $appointments = Appointment::where([
            ['start', '>', $request->get('from')],
            ['end', '<=', $request->get('to')],
        ])->get();
        return $appointments->toArray();

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

        $appointment->save();

        return $appointment->toArray();
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
