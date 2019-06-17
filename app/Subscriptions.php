<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscriptions
 * @package App
 */
class Subscriptions extends Model
{
    /**
     * @param string $email
     * @return Subscriptions
     */
    public static function add(string $email)
    {
        $sub = new static;
        $sub->email = $email;
        $sub->save();
        return $sub;
    }

    /**
     * @param string $email
     */
    public function edit(string $email)
    {
        $this->email = $email;
        $this->save();
    }

    /**
     * Generate verify token
     */
    public function generateToken()
    {
        $this->token = str_random(100);
        $this->save();
    }
}
