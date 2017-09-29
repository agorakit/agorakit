<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Session;
use App;

class RedirectLang
{
    /**
     * The availables languages.
     *
     * @array $languages
     */
    protected $locales = null;

    public function __construct()
    {
        $this->locales = Config::get('app.locales');
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
        // Detect the bot case => if bot we do nothing
        $isBot = isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT']);

        /*
        * If User has never been logged => it will be redirected to his local url
        */
        if (!$request->cookie('locale') && !$isBot) {
            $locale = App::getLocale();

            // if local doesn't match the current user local => we redirect
            $preferedLocale = $request->getPreferredLanguage($this->locales);

            // If local doesn't match the current user => we redirect the user to the correct url
            if ($locale !== $preferedLocale) {
                // Get Locales of the preferred local
                Session::put('locale', $preferedLocale);
            }
        } elseif ($request->cookie('locale')) {
            Session::put('locale', $request->cookie('locale'));
        }

        if ($request->has('force_locale')) {
            Session::put('locale', $request->get('force_locale'));
            App::setLocale(Session::get('locale', config('app.locale')));

            return $next($request)->withCookie(cookie()->forever('locale', Session::get('locale')));
        }

        App::setLocale(Session::get('locale', config('app.locale')));

        return $next($request);
    }
}
