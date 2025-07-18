<?php

namespace Agorakit\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    'Agorakit\Model'                  => 'Agorakit\Policies\ModelPolicy',
    \Agorakit\Action::class           => \Agorakit\Policies\ActionPolicy::class,
    \Agorakit\Comment::class          => \Agorakit\Policies\CommentPolicy::class,
    \Agorakit\Discussion::class       => \Agorakit\Policies\DiscussionPolicy::class,
    \Agorakit\File::class             => \Agorakit\Policies\FilePolicy::class,
    \Agorakit\Group::class            => \Agorakit\Policies\GroupPolicy::class,
    \Agorakit\User::class             => \Agorakit\Policies\UserPolicy::class,
    \Agorakit\Membership::class       => \Agorakit\Policies\MembershipPolicy::class,
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
