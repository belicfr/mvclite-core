<?php

namespace MvcliteCore\Database\ORM\Migrator\Builder;

interface ColumnType
{
    public static function make(string $name): ColumnType;
}