<?php namespace App\Http\Middleware;
use Illuminate\Support\Facades\Session;
use Closure;

class frontEndHasSession {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Session::has('User'))
		{
			return Redirect('dashboard.html');
		}
		return $next($request);
	}

}
