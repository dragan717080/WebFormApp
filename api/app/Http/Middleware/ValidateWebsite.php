<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Validates fields 'website' in request body of incoming request.
 */
class ValidateWebsite
{
    public function handle(Request $request, Closure $next)
    {
        $website = $request->input('website');

        $websitePattern = '/^https:\/\/.+$/';
        $isCorrectWebsiteFormat = !empty($website) ? preg_match($websitePattern, $website) : true;

        if (!$isCorrectWebsiteFormat) {
            return response()->json('Invalid website format.', 400);
        }

        return $next($request);
    }
}
