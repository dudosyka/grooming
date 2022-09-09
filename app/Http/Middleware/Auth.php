<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class Auth
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
        if ($request->header('Authorization')) {
            $auth = explode(' ', $request->header('Authorization'))[0];
            $user = User::query()->where('api_token', '=', $auth)->get()->all();
            if (count($user)) {
                $user = $user[0];
                $request->setUserResolver(function () use ($user) {
                   return $user;
                });
            } else {
                abort(403);
            }
        } else {
            abort(403);
        }
        return $next($request);
    }
}
