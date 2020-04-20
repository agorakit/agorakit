<?php

namespace App\Http\Middleware;

use App;
use Auth;
use Closure;
use Config;
use Session;

class SetLocale
{
    /**
    * The availables languages.
    *
    * @array $languages
    */
    protected $locales = null;

    public function __construct()
    {
        $this->locales = config('app.locales');
    }

    /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure                 $next
    *
    * @return mixed
    */
    public function handle($request, Closure $next)
    {

        // If user agent is a bot, do nothing
        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
            return $next($request);
        }

        // Locale priority order :
        // 1- Parameter force_locale from request
        // 2- Locale from user settings
        // 3- Locale from session
        // 4- autodetect from browser
        // 5- default application locale


        // 1- Parameter force_locale from request
        if ($request->has('force_locale') && in_array($request->get('force_locale'), $this->locales)) {
            $locale = $request->get('force_locale');
            Session::put('locale', $locale);


            // store locale in user preferences
            if (Auth::check()) {
                $user = Auth::user();
                $user->setPreference('locale', $locale);
            }

            App::setLocale($locale);
            return $next($request)->withCookie(cookie()->forever('locale', $locale));
        }


        // 2- Locale from user settings
        if (Auth::check() && Auth::user()->getPreference('locale')) {
            $locale = Auth::user()->getPreference('locale');
            Session::put('locale', $locale);
            App::setLocale($locale);
            return $next($request)->withCookie(cookie()->forever('locale', $locale));
        }

        // 3- Locale from session
        if (Session::get('locale')) {
            $locale = Session::get('locale');

            // store locale in user preferences
            if (Auth::check()) {
                $user = Auth::user();
                $user->setPreference('locale', $locale);
            }
            App::setLocale($locale);
            return $next($request)->withCookie(cookie()->forever('locale', $locale));
        }

        // 4- autodetect from browser
        $locale = $request->getPreferredLanguage($this->locales);
        if ($locale) {
            // store locale in user preferences
            if (Auth::check()) {
                $user = Auth::user();
                $user->setPreference('locale', $locale);
            }
            App::setLocale($locale);
            return $next($request)->withCookie(cookie()->forever('locale', $locale));
        }

        // 5- default application locale
        App::setLocale(config('app.locale'));
        return $next($request);
    }
}
