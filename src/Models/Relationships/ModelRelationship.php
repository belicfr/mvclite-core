<?php

namespace MvcliteCore\Models\Relationships;

use MvcliteCore\Engine\Precepts\Naming;
use MvcliteCore\Models\Model;

class ModelRelationship
{
    private Model $leftModel;

    private string $rightModel;

    private $queryExtension;

    public function __construct(Model $leftModel, string $rightModel, callable $queryExtension = null)
    {
        $this->leftModel = $leftModel;
        $this->rightModel = $rightModel;
        $this->queryExtension = $queryExtension;
    }

    public function getLeftModel(): Model
    {
        return $this->leftModel;
    }

    public function getRightModel(): string
    {
        return $this->rightModel;
    }

    public function getQueryExtension(): callable
    {
        return $this->queryExtension;
    }

    public function hasQueryExtension(): bool
    {
        return $this->queryExtension !== null;
    }
}