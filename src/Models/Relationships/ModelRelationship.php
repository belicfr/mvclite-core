<?php

namespace MvcliteCore\Models\Relationships;

use MvcliteCore\Engine\Precepts\Naming;
use MvcliteCore\Models\Model;

class ModelRelationship
{
    private Model $leftModel;

    private string $rightModel;

    public function __construct(Model $leftModel, string $rightModel, ?string $customColumnName = null)
    {
        $this->leftModel = $leftModel;
        $this->rightModel = $rightModel;
    }

    public function getLeftModel(): Model
    {
        return $this->leftModel;
    }

    public function getRightModel(): string
    {
        return $this->rightModel;
    }
}