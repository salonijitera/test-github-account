<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

class AttemptLock
{
    use SerializesModels;

    /**
     * The authentication guard name.
     *
     * @var string
     */
    public $guard;

    /**
     * The user retrieved and validated from the User Provider.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    public function __construct($guard, $user)
    {
        $this->user = $user;
        $this->guard = $guard;
    }
}
