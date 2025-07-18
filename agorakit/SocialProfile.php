<?php

namespace Agorakit;

use Illuminate\Database\Eloquent\Model;

class SocialProfile extends Model
{
    protected $table = 'social_profiles';

    protected $fillable = ['user_id', 'provider', 'provider_id'];

    /**
     * The user linked to this social profile.
     */
    public function user()
    {
        return $this->belongsTo(\Agorakit\User::class);
    }
}
