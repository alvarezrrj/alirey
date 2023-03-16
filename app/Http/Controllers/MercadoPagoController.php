<?php

namespace App\Http\Controllers;

use App\Events\NewBookingEvent;
use App\Models\Booking;
use App\SD\SD;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MercadoPagoController extends Controller
{

    static function create_or_get_preference($booking, $price)
    {
        \MercadoPago\SDK::setAccessToken(config('mercadopago.mp_token'));

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

        // Retrieve url from config file, depends on environment
        $pref->notification_url = App::environment('local')
            ? config('mercadopago.local_notification_url')
            : route(config('mercadopago.dist_notification_url_name'));

        $pref->save();

        $booking->pref_id = $pref->id;
        $booking->pref_expiry = $pref->expiration_date_to;
        $booking->save();

        return $pref->id;
    }

    static function refund(int $id) : array
    {
        $response = Http::withHeaders([
            'X-Idempotency-Key' => uniqid('', true),
            'Authorization' => 'Bearer '.config('mercadopago.mp_token'),
            'Content-Type' => 'application/json'
        ])->post('https://api.mercadopago.com/v1/payments/'.$id.'/refunds', [
            'amount' => null
        ]);

        return $response->json();
    }

    public function webhook(Request $request) 
    {
        if($request->input('type') == 'payment')
        {
            \MercadoPago\SDK::setAccessToken(config('mercadopago.mp_token'));
            $payment = \MercadoPago\Payment::find_by_id($request->input('data.id'));
            $paid_amount = $payment->transaction_details->total_paid_amount;
            $mp_id = $payment->id;

            if ($payment->status == "approved") {

                $merchant_order = \MercadoPago\MerchantOrder::find_by_id($payment->order->id);
                $booking_id = $merchant_order->external_reference;

                $booking = Booking::find($booking_id);
                
                // Update payment status
                DB::transaction(function () use ($booking, $mp_id, $paid_amount) {
                    $booking->payment()->update([
                        'mp_id' => $mp_id,
                        'amount' => $paid_amount,
                        'status' => SD::PAYMENT_MP,
                    ]);
                });

                // Removing preference attributes from booking keeps it from being deleted
                // by scheduled task
                $booking->pref_id = null;
                $booking->pref_expiry = null;
                $booking->save();

                // Now that payment is confirmed, dispatch NewBookingEvent. 
                // Sends emails.
                //NewBookingEvent::dispatch($booking);
            }
        }
        return response('', 200);
    }

}
