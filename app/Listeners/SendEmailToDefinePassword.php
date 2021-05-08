<?php

namespace CodeShopping\Listeners;

use CodeShopping\Events\UserCreatedEvent;


class SendEmailToDefinePassword
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        $user = $event->getUser();
        $token = \Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        //$user->notify(new MyNotification($token));

    }
}
