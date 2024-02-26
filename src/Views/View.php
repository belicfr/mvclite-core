<?php

namespace MvcliteCore\Views;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\Entities\Image;
use MvcliteCore\Engine\InternalResources\Delivery;
use MvcliteCore\Engine\InternalResources\Storage;
use MvcliteCore\Engine\Session\Session;
use MvcliteCore\Router\Request;
use MvcliteCore\Views\Exceptions\NotFoundViewException;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class View
{
    public static function render(string $viewPath, array $props = [])
    {
        $absoluteViewPath = "./src/Views/$viewPath.twig";

        if (!file_exists($absoluteViewPath))
        {
            $error = new NotFoundViewException();
            $error->render();
        }

        $props["delivery"] = Delivery::get();
        $props["session"] = [
            "isLogged" => Session::isLogged(),
            "id" => Session::getSessionId(),
            "user" => Session::getUserAccount(),
        ];

        echo "<noscript>
                  Veuillez activer le JavaScript.
                  <meta http-equiv=\"refresh\" content=\"0; url=" . ROUTE_PATH_PREFIX . "nojs.php\" />
              </noscript>";

        $twigLoader = new FilesystemLoader("./src/Views");
        $twigEnvironment = new Environment($twigLoader);

        $routeFunction = new TwigFunction("route", fn ($routeName) => route($routeName));
        $requestFunction = new TwigFunction("request", fn () => request());
        $deliveryFunction = new TwigFunction("delivery", fn () => delivery());
        $getFunction = new TwigFunction("get", fn ($key) => get($key));
        $postFunction = new TwigFunction("post", fn ($key) => post($key));
        $resourceFunction = new TwigFunction(
            "resource",
            fn ($rpath, $type = "", $importMethod = "") => Storage::include($rpath, $type, $importMethod),
            [
                "is_safe" => [
                    "html",
                ],
            ]
        );
        $mediaFunction = new TwigFunction("media", function ($subPath)
        {
            return "/src/Resources/Medias/$subPath";
        });

        $twigEnvironment->addFunction($routeFunction);
        $twigEnvironment->addFunction($requestFunction);
        $twigEnvironment->addFunction($deliveryFunction);
        $twigEnvironment->addFunction($getFunction);
        $twigEnvironment->addFunction($postFunction);
        $twigEnvironment->addFunction($resourceFunction);
        $twigEnvironment->addFunction($mediaFunction);

        echo $twigEnvironment->render("$viewPath.twig", $props);
    }

    public static function nativeRender(string $viewPath, array $props = [])
    {
        $absoluteViewPath = "./src/Views/$viewPath.php";

        if (!file_exists($absoluteViewPath))
        {
            $error = new NotFoundViewException();
            $error->render();
        }

        extract($props);
        $props = Delivery::get();

        echo "<noscript>
                  Veuillez activer le JavaScript.
                  <meta http-equiv=\"refresh\" content=\"0; url=" . ROUTE_PATH_PREFIX . "nojs.php\" />
              </noscript>";

        include $absoluteViewPath;
    }
}