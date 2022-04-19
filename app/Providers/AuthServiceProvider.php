<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model'                  => 'App\Policies\ModelPolicy',
        \App\Models\Action::class           => \App\Policies\ActionPolicy::class,
        \App\Models\Comment::class          => \App\Policies\CommentPolicy::class,
        \App\Models\Discussion::class       => \App\Policies\DiscussionPolicy::class,
        \App\Models\File::class             => \App\Policies\FilePolicy::class,
        \App\Models\Group::class            => \App\Policies\GroupPolicy::class,
        \App\Models\User::class             => \App\Policies\UserPolicy::class,
        \App\Models\Membership::class       => \App\Policies\MembershipPolicy::class,
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
    }
}
