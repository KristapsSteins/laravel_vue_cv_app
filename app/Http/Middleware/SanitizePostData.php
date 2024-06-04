<?php

namespace App\Http\Middleware;

use Closure;

class SanitizePostData
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
        if ($request->isMethod('post')) {
            $data = $request->all();

            array_walk_recursive($data, function (&$item, $key) {
                $item = htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
            });

            $request->merge($data);
        }

        return $next($request);
    }
}