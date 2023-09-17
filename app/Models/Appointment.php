<?php

namespace App\Models;

use App\ClientBooking\AppointmentOpeningHourValidator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;

/**
 * @property string client_name
 * @property string start
 * @property string end
 * @package App\Models
 */
class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';

    public function ableToBook(): bool
    {
        return
            $this->validateToOpeningHours()
            && $this->unBooked();
    }

    private function validateToOpeningHours(): bool
    {
        $openingHours = OpeningHour::all();
        $appointmentValidator = new AppointmentOpeningHourValidator();
        foreach ($openingHours as $openinghHour) {
            if ($appointmentValidator->validate($this, $openinghHour)) {
                return true;
            }
        }
        return false;
    }

    private function unBooked(): bool
    {
        $appointments = Appointment::where([
            ['start', '>=', $this->start],
            ['start', '<=', $this->start]
        ])
        ->orWhere([
            ['end', '>=', $this->end],
            ['end', '<=', $this->end],
        ])
        ->count();

        return $appointments === 0;
    }
}
