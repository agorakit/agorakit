<?php namespace App\Http\Middleware;

use Closure, Config;

class RedirectLangByDomain {

    /**
     * The availables languages.
     *
     * @array $languages
     */
    protected $locales = null;

    public function __construct(){
        $this->locales = Config::get('app.locales', [Config::get('app.local') => ['url' => Config::get('app.url')]]);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = Config::get('app.local');

        // if current local url match request url => we keep the current lang
        foreach ($this->locales as $lang => $local) {
            if ($local['url'] == $request->url()) {
                break; //  keep the current lang definition
            }
        }

        \App::setLocale($lang);

        return $next($request);
    }

}