<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'      => 'App\Policies\ModelPolicy',
        'App\Action'     => 'App\Policies\ActionPolicy',
        'App\Comment'    => 'App\Policies\CommentPolicy',
        'App\Discussion' => 'App\Policies\DiscussionPolicy',
        'App\File'       => 'App\Policies\FilePolicy',
        'App\Group'      => 'App\Policies\GroupPolicy',
        'App\User'       => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('ltm-admin-translations', function ($user) {
            /* @var $user \App\User */
            return $user && $user->isAdmin();
        });

        Gate::define('ltm-bypass-lottery', function ($user) {
            /* @var $user \App\User */
            return $user && ($user->isAdmin() || $user->is_editor);
        });

        Gate::define('ltm-list-editors', function ($user, $connection_name, &$user_list) {
            /* @var $user \App\User */
            /* @var $connection_name string */
            /* @var $query  \Illuminate\Database\Query\Builder */
            $query = $user->on($connection_name)->getQuery();

            // modify the query to return only users that can edit translations and can be managed by $user
            // if you have a an editor scope defined on your user model you can use it to filter only translation editors
            //$user_list = $user->scopeEditors($query)->orderby('id')->get(['id', 'email', 'name']);
            $user_list = $query->orderby('id')->get(['id', 'email']);

            // if the returned list is empty then no per locale admin will be shown for the current user.
            return $user_list;
        });

        //
    }
}
