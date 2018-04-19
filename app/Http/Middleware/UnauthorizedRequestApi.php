<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class UnauthorizedRequestApi
{
    /**
     * @param         $request
     * @param Closure $next
     *
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
//        if(!\Auth::check()) {
//            return new JsonResponse('Unauthorized request', 401);
//        }

        return $next($request);
    }
}
