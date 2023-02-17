<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FindPendingPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(! $request->user()->isAdmin() )
        {
            $pending_booking = $request->user()->bookings()
                ->where('pref_id', '!=', null)
                ->first();

            session()->flash('pending_payment', $pending_booking?->id);
        }


        return $next($request);
    }
}
