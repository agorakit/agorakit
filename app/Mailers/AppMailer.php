<?php
namespace App\Mailers;
use Mail;
use App\User;

class AppMailer
{

  /**
  * Deliver the email confirmation.
  *
  * @param  User $user
  * @return void
  */
  public function sendEmailConfirmationTo(User $user)
  {
    Mail::send('emails.confirm', ['user' => $user], function ($message) use ($user) {
      $message->to($user->email, $user->name)
      ->subject('Confirm your email')
      ->from('admin@example.com'); // TODO configurable in .env
    });

  }


}
