<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{

    static function create_or_get_preference($booking, $price)
    {
        \MercadoPago\SDK::setAccessToken(env('MP_TOKEN'));

        if(isset($booking->pref_expiry))
        {
            if( Carbon::now()->gte($booking->pref_expiry) )
            {
                $booking->delete();
                // remove booking id from session to keep navigation.blade.php
                // from showing the notification badge 
                session()->forget('pending_payment');
                return false;
            } else {
                return $booking->pref_id;
            }
        }

        $now = Carbon::now();
    
        $pref = new \MercadoPago\Preference();

        // Excluded payment methods
        $payment_methods = [ 
            'excluded_payment_methods' => [], 
            'excluded_payment_types'   => [
                ['id' => 'ticket']
            ]   
        ];

        $item                   = new \MercadoPago\Item();
        $item->id               = "$booking->id";
        $item->title            = 'Sesión de bioconstelación';
        $item->description      = "Sesión de bioconstelación el día {$booking->day->format('d/m/Y')} a las {$booking->slot->start->format('H:i')} horas";
        $item->picture_url      = "https://rodrigoalvarez.co.uk/alirey/assets/img/sathy/alicia_rey_constelaciones_logo_horizontal.png";
        $item->category_id      = "services";
        $item->quantity         = 1;
        $item->currency_id      = "ARS";
        $item->unit_price       = $price;

        $pref->items            = array($item);
        $pref->payment_methods  = $payment_methods;

        // Forces payment not to remain 'pending'
        $pref->binary_mode      = true;

        $pref->expires          = true;
        // Now in ISO 8601 format
        $pref->expiration_date_from = $now->format('c');
        $pref->expiration_date_to   = $now->addMinutes(10)->format('c');

        $pref->back_urls        = [
            'success' => route('user.bookings.confirmation', $booking),
            'failure' => route('user.bookings.failure'),
        ];

        // Allows me to access the booking ID from the webhook notification
        $pref->external_reference   = $booking->id;              

        // Referencia en el resumen de tarjeta
        $pref->statement_descriptor = "AR-Bioconstelaciones";   

        $pref->notification_url = "https://aa44914e-dbc5-46d5-acf8-174fb163d2df.mock.pstmn.io?source_news=webhooks";

        $pref->save();

        $booking->pref_id = $pref->id;
        $booking->pref_expiry = $pref->expiration_date_to;
        $booking->save();

        return $pref->id;
    }

    public function webhook(Request $request) 
    {
        if($request->input('type') == 'payment')
        {
            \MercadoPago\SDK::setAccessToken(env('MP_TOKEN'));
            $payment = \MercadoPago\Payment::find_by_id($request->input('data.id'));
            $paid_amount = $payment->transaction_details->total_paid_amount;
            $mp_id = $payment->id;

            if ($payment->status == "approved") {

                $merchant_order = \MercadoPago\MerchantOrder::find_by_id($payment->order->id);
                $booking_id = $merchant_order->external_reference;

                Booking::find($booking_id)->payment()->update([
                    'mp_id' => $mp_id,
                    'amount' => $paid_amount,
                    'status' => SD::PAYMENT_MP,
                ]);

                // TO DO
                // Send emails
            }
        }
        return response('', 200);
    }

}
