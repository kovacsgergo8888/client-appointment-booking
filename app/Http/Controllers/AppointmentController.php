<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentCollectionRequest;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        var_dump($appointments[0]);die;
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

        // $validator = Validator::make($appointment)

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
