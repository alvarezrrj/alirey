<?php

namespace App\SD;

class SD {
    const admin  = 'admin';
    const client = 'client';

    const PAYMENT_PENDING   = 'Pending';
    const PAYMENT_CASH      = 'Cash';
    const PAYMENT_REFUNDED  = 'Refunded';
    const PAYMENT_MP        = 'MercadoPago';

    const BOOKING_PENDING   = 'Pending';
    const BOOKING_COMPLETED = 'Completed';
    const BOOKING_CANCELLED = 'Cancelled';
}