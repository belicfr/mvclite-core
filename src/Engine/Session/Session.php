<?php

namespace MvcliteCore\Engine\Session;

use MvcliteCore\Database\Database;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Security\Password;
use MvcLite\Models\User;
use stdClass;

/**
 * Session manager class.
 *
 * @author belicfr
 */
class Session
{
    /** $_SESSION key for current session id. */
    private const AUTH_SESSION_VARIABLE = "AUTH_SESSION_ID";

    /**
     * @return bool If visitor has a session id
     */
    public static function isLogged(): bool
    {
        return isset($_SESSION[self::AUTH_SESSION_VARIABLE]);
    }

    /**
     * @return int|false Current session id if logged;
     *                   else FALSE
     */
    public static function getSessionId(): int|false
    {
        return self::isLogged()
            ? $_SESSION[self::AUTH_SESSION_VARIABLE]
            : false;
    }

    public static function attemptLogin(string $login, string $password): bool
    {
        $query = "SELECT * FROM "
                . AUTHENTIFICATION_COLUMNS["table"]
                . " WHERE "
                . AUTHENTIFICATION_COLUMNS["login"]
                . " = ?";

        $user = Database::query($query, $login)
            ->get();

        $attemptState
            = $user
              && Password::verify($password,
                                  $user[AUTHENTIFICATION_COLUMNS["password"]]);

        if ($attemptState)
        {
            self::setSessionId($user["id"]);
        }

        return $attemptState;
    }

    /**
     * Force to log in with given user id.
     *
     * @param int $sessionId Session id
     */
    private static function setSessionId(int $sessionId): void
    {
        $_SESSION[self::AUTH_SESSION_VARIABLE] = $sessionId;
    }

    /**
     * @return stdClass|null Account User object if visitor is logged;
     *                       else NULL
     */
    public static function getUserAccount(): stdClass|null
    {
        $user = [];

        if (self::isLogged())
        {
            $user = User::select()
                ->where("id", self::getSessionId())
                ->execute()
                ->publish();
        }

        return count($user)
            ? $user[0]
            : null;
    }

    public static function logout(): void
    {
        session_destroy();
    }
}