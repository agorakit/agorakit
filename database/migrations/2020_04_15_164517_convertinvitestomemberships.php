<?php

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Convertinvitestomemberships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\App\Models\Invite::whereNull('claimed_at')->get() as $invite) {

            //$this->info('converting invite to new membership');

            $user = User::firstOrCreate(['email' => $invite->email]);
            $group = Group::find($invite->group_id);

            if ($group && $user) {
                if ($user->isMemberOf($group)) {
                    //$this->error('User already member : '.$invite->email);
                } else {
                    $membership = \App\Models\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $group->id]);
                    $membership->membership = \App\Models\Membership::INVITED;
                    $membership->save();

                    //$this->line('User added to membership invite : '.$invite->email);
                }
            }

            $invite->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
