<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string from
 * @property ?string to
 * @property string from_time
 * @property string to_time
 * @property string repeat
 * @property int day_of_week
 * @package App\Models
 */
class OpeningHour extends Model
{
    use HasFactory;

    protected $tableName = 'opening_hours';
}
