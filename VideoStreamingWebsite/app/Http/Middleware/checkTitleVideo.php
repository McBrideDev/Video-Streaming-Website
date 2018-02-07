<?php

namespace App\Http\Middleware;

use Closure;

class checkTitleVideo
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
        if (!empty($request->string_Id))
        {
            $lang = str_replace('/','', getLang());
            
            return Redirect($lang.'/watch/'.$request->string_Id.'/xstreamer-video.html');
        }
        return $next($request);
    }
}
