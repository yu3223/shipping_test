<?php 

namespace App\Services;

class User
{
    /**
     * User key
     *
     * @var null|int
     */
    private static $u_key;
    
    /**
     * Get user key
     *
     * @return integer|null
     */
    public static function getUserKey(): ?int
    {
        return self::$u_key;
    }

    /**
     * Set user key
     *
     * @param int $user_key
     * @return void
     */
    public static function setUserKey(string $user_key)
    {
        self::$u_key = $user_key;
    }
}