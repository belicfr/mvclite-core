<?php

namespace MvcliteCore\Engine\InternalResources;

use MvcliteCore\Engine\Entities\Image;
use MvcliteCore\Engine\InternalResources\Exceptions\InvalidImportMethodException;
use MvcliteCore\Engine\InternalResources\Exceptions\InvalidResourceTypeException;
use MvcliteCore\Engine\InternalResources\Exceptions\NotFoundResourceException;

class Storage
{
    /** RegEx for .css files. */
    private const REGEX_CSS_FILE = "/(.*).css$/";

    /** RegEx for .js files. */
    private const REGEX_JS_FILE = "/(.*).js$/";

    /** JS script tag import method. */
    private const JS_IMPORT_METHODS = [
        "async", "defer",
    ];

    /**
     * @return string /src/Engine/ folder path
     */
    public static function getEnginePath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . ROUTE_PATH_PREFIX . "/src/Engine";
    }

    /**
     * @return string /src/Resources/ folder path
     */
    public static function getResourcesPath(): string
    {
        return $_SERVER['DOCUMENT_ROOT'] . ROUTE_PATH_PREFIX . "/src/Resources";
    }

    /**
     * @return string /src/Views/Components/ folder path
     */
    public static function getComponentsPath(): string
    {
        return $_SERVER["DOCUMENT_ROOT"] . ROUTE_PATH_PREFIX . "/src/Views/Components";
    }

    /**
     * Attempt to include a supported file in a view.
     *
     * @param string $relativePath Relative path to file
     * @param string $type Optional file type (by default
     *                     automatically defined)
     * @return string Generated HTML content
     */
    public static function include(string $relativePath, string $type = "", string $importMethod = ""): string
    {
        $pathPrefix = ROUTE_PATH_PREFIX[strlen(ROUTE_PATH_PREFIX) - 1] == '/'
            ? substr(ROUTE_PATH_PREFIX, 0, strlen(ROUTE_PATH_PREFIX) - 1)
            : ROUTE_PATH_PREFIX;

        $relativePath = $relativePath[0] == '/'
            ? $relativePath
            : '/' . $relativePath;

        $absolutePath = self::getResourcesPath() . $relativePath;

        $type = strtolower($type);
        $html = "";

        if (!file_exists($absolutePath))
        {
            $error = new NotFoundResourceException($relativePath);
            $error->render();
        }

        if ($type == "css" || preg_match(self::REGEX_CSS_FILE, $relativePath))
        {
            if ($importMethod != "")
            {
                $error = new InvalidImportMethodException();
                $error->render();
            }

            $html = "<link rel='stylesheet' href='$pathPrefix/src/Resources$relativePath' />";
        }
        else if ($type == "js" || preg_match(self::REGEX_JS_FILE, $relativePath))
        {
            if ($importMethod != "" && !in_array($importMethod, self::JS_IMPORT_METHODS))
            {
                $error = new InvalidImportMethodException();
                $error->render();
            }

            $html = "<script src='$pathPrefix/src/Resources/$relativePath' $importMethod></script>";
        }
        else
        {
            $error = new InvalidResourceTypeException();
            $error->render();
        }

        //echo $html;

        return $html;
    }

    /**
     * Attempt to include an existing component.
     *
     * @param string $name Component name
     * @param array $props Props to share with component
     */
    public static function component(string $name, array $props = []): void
    {
        $props["props"] = Delivery::get();
        extract($props);

        include "src/Views/Components/$name.php";
    }

    /**
     * @param Image $image
     * @param string $subfoldersRelativePath
     * @return string Uploaded image absolute path
     */
    public static function createImage(Image $image, string $subfoldersRelativePath = ""): string
    {
        $imageFolderAbsolutePath = self::getResourcesPath()
            . "/$subfoldersRelativePath/";

        $imageAbsolutePath = $imageFolderAbsolutePath
            . $image->getName();

        if (!file_exists($imageFolderAbsolutePath))
        {
            mkdir($imageFolderAbsolutePath, recursive: true);
        }

        $file = fopen($imageAbsolutePath, 'w');
        fwrite($file, $image->getContent());
        fclose($file);

        return $imageAbsolutePath;
    }
}