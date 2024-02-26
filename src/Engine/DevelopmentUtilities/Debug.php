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
//        $debugCss = file_get_contents(__DIR__
//            . "/DebugRendering/rendering.css");

        $debugCss = 'div[mvclite-dd] {
                       border: solid 3px darkorange;
                       border-radius: 10px;
                       padding: 10px;
                       color: white;
                       font-family: monospace;
                       background: #212121;
                       margin-bottom: 20px;
                       box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
                     }
                     div[mvclite-dd]::before {
                       content: "DEBUG:";
                       color: darkorange;
                     }
                     div[mvclite-dd] > pre {
                       word-wrap: anywhere;
                     }';

        echo "<style>$debugCss</style>";
    }
}