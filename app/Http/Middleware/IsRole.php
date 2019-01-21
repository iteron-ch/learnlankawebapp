<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;

class IsRole {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next,
                $usertype1,$usertype2 = NULL,$usertype3 = NULL,
                $usertype4 = NULL,$usertype5 = NULL)
	{
            if (session()->has('user'))
            {
                $user = session()->get('user');
                if(in_array($user['user_type'], array($usertype1,$usertype2,$usertype3,$usertype4,$usertype5))){
                     return $next($request);
                }

            }
            return new RedirectResponse(url('/'));
	}

}
