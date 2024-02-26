<?php

namespace MvcliteCore\Engine\DevelopmentUtilities;

use MvcliteCore\Engine\InternalResources\Storage;

class Debug
{
    /**
     * Dump.
     *
     * @param mixed ...$values Values to dump
     */
    public static function dump(mixed ...$values): void
    {
        self::importCss();

        foreach ($values as $value)
        {
            echo "<div mvclite-dd>
                    <pre>";

            var_dump($value);

            echo "</pre>
                  </div>";
        }
    }

    /**
     * Die and Dump.
     *
     * @param mixed ...$values Values to dump
     */
    public static function dd(mixed ...$values): void
    {
        self::dump(...$values);
        die;
    }

    /**
     * Import debug rendering CSS.
     */
    private static function importCss(): void
    {
        $debugCss = file_get_contents($_SERVER['DOCUMENT_ROOT']
            . "/vendor/belicfr/mvclite-core/src/Engine/DevelopmentUtilities/DebugRendering/rendering.css");

        echo "<style>$debugCss</style>";
    }
}