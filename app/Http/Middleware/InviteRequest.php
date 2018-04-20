<?php

namespace App\Http\Middleware;

use App\Models\Invite;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class InviteRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$this->checkToken($request)) {
            return redirect()->route('invalidToken', ['token' => $request->get('token') ?? 'false']);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function checkToken(Request $request) {
        if(!$request->has('token')) {
            return false;
        }

        $token = Invite::where(['token' => $request->get('token'), 'is_valid' => true])->first();

        if(null === $token) {
            return false;
        }

        try{
            $created_at = Carbon::parse($token->created_at);
        } catch (\Exception $exception ) {
            return false;
        }

        if(time() > $token->validity + $created_at->timestamp) {
            return false;
        }

        return true;
    }

}
