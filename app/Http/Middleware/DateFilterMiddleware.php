<?php

namespace App\Http\Middleware;

use App\Services\DateFilterService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DateFilterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only handle date filter for user routes
        if (auth()->check() && !auth()->user()->isAdmin()) {
            $filter = $request->get('filter');
            $customDate = $request->get('custom_date');

            // If filter parameters are provided, update session
            if ($filter) {
                $customDateObj = $customDate ? Carbon::parse($customDate) : null;
                DateFilterService::setFilter($filter, $customDateObj);
            }
        }

        return $next($request);
    }
}
