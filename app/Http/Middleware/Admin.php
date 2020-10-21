<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\User;

/**
 * The Foo class.
 */
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * 
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($apiToken=$request->header('api_token')) { 
            
            if ($user=User::where('api_token', $apiToken)->first()) {
                
                $type = $user->type;
                //dd($type);
                if ($type == 'admin') {
                    return $next($request);
                }
            }
           
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
