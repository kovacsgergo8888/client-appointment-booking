<?php

namespace App\ClientBooking;

enum Repeat: string {
    case NO_REPEAT = 'NO_REPEAT';
    case WEEKLY = 'WEEKLY';
    case ODD_WEEKS = 'ODD_WEEKS';
    case EVEN_WEEKS = 'EVEN_WEEKS';
}
