<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Validates fields 'phone' in request body of incoming request.
 */
class ValidatephoneNumber
{
    public function handle(Request $request, Closure $next)
    {
        $phoneNumber = $request->input('phone');

        if ($phoneNumber[0] !== '+') {
            return response()->json("Phone country code wasn't provided.");
        }

        $isCorrectLength = strlen($phoneNumber) > 7 && strlen($phoneNumber) < 17;
        $isCorrectphoneNumberFormat = !empty($phoneNumber) ? $isCorrectLength : true;

        if (!$isCorrectphoneNumberFormat) {
            return response()->json('Invalid phone number.', 400);
        }

        return $next($request);
    }
}
