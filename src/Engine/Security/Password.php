<?php

namespace MvcliteCore\Engine\Security;

/**
 * Password management class.
 *
 * @author belicfr
 */
class Password
{
    /**
     * Returns a hashed string from given password.
     *
     * @param string $password Original password
     * @return string Hashed password
     */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Returns if given password corresponds to given hash.
     *
     * @param string $password Password to verify
     * @param string $hash Hashed password
     * @return bool Verification state
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}