<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use App;
use Redirect;

class Language
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
        // Make sure current locale exists.
        $segments = $request->segments();
        if(count($segments) > 0) {
            $locale = $request->segment(1);

            //check if first segment is a proper locale
            if(array_key_exists($locale, Config::get('app.locales'))) {
                App::setLocale($locale);
            } else {
                array_unshift($segments , Config::get('app.fallback_locale'));

                $newUrl = $this->redirect_if_locale_is_null($segments, $_SERVER);
                return Redirect::to($newUrl);
            }

            return $next($request);
        } else {
            array_unshift($segments , Config::get('app.fallback_locale'));

            $newUrl = $this->redirect_if_locale_is_null($segments, $_SERVER);
            return Redirect::to($newUrl);
        }

        /*
        if ( strlen($locale)< 6 || ! array_key_exists(substr($locale,5), Config::get('app.locales'))) {
            //$segments = $request->segments();
            array_unshift($segments , Config::get('app.fallback_locale'));
            //$segments[0] = Config::get('app.fallback_locale');
            $newUrl = implode('/', $segments);
            if (array_key_exists('QUERY_STRING', $_SERVER))
                $newUrl .= '?'.$_SERVER['QUERY_STRING'];
            App::setLocale(Config::get('app.fallback_locale'));
            return Redirect::to($newUrl);
        }
        else {
            //$segments = $request->segments();
            $segments[0] = substr($locale,5);
            $newUrl = implode('/', $segments);
            if (array_key_exists('QUERY_STRING', $_SERVER))
                $newUrl .= '?'.$_SERVER['QUERY_STRING'];
            App::setLocale(substr($locale,5));
            //return Redirect::to($newUrl);
        }
        return $next($request);
        */
    }

    private function redirect_if_locale_is_null($segments, $server_var) {
        $newUrl = implode('/', $segments);

        if (array_key_exists('QUERY_STRING', $server_var))
            $newUrl .= '?'.$server_var['QUERY_STRING'];
        App::setLocale(Config::get('app.fallback_locale'));

        return $newUrl;
    }
}
