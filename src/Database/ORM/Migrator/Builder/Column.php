<?php

namespace MvcliteCore\Database\ORM\Migrator\Builder;

class Column
{
    private string $name;

    private array $constraints;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->constraints = [];
    }

    private function nullable(bool $isNullable = true): Column
    {
        $this->addConstraint("NOT_NULL");
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array Column constraints
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }

    /**
     * @param string $constraint Constraint name
     * @return string Constraint SQL expression
     */
    public function getConstraint(string $constraint): string
    {
        return $this->getConstraints()[$constraint];
    }

    /**
     * @return string Constraints as string
     */
    public function getConstraintsAsString(): string
    {
        return implode(' ', array_values($this->getConstraints()));
    }

    /**
     * Adds a new constraint.
     *
     * @param string $name Constraint name
     * @param string $value Constraint SQL expression
     */
    private function addConstraint(string $name, string $value): void
    {
        $this->constraints[$name] = $value;
    }

    /**
     * Removes given constraint.
     *
     * @param string $constraint Constraint name
     */
    private function removeConstraint(string $constraint): void
    {
        unset($this->constraints[$constraint]);
    }
}