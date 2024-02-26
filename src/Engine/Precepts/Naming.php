<?php

namespace MvcliteCore\Engine\Precepts;

class Naming
{
    public static function getClassName(string $staticStructure): string
    {
        $path = explode('\\', $staticStructure);

        return array_pop($path);
    }

    /**
     * @param object $object Object used to retrieve its class name
     * @return string Class name
     */
    public static function getClassNameByObject(object $object): string
    {
        return self::getClassName(get_class($object));
    }

    /**
     * @param string $camelString camelCase string
     * @return string snake_case string
     */
    public static function camelToSnake(string $camelString): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $camelString));
    }

    /**
     * @param string $snakeString snake_case string
     * @return string camelCase string
     */
    public static function snakeToCamel(string $snakeString): string
    {
        return lcfirst(str_replace('', '', ucwords($snakeString, '')));
    }
}