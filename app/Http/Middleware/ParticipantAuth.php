<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ParticipantAuth
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
        // Check if participant is logged in
        if (!session('participant_logged_in') || !session('participant_uid')) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.'
                ], 401);
            }
            
            return redirect('/peserta/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
