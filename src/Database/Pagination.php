<?php

namespace MvcliteCore\Database;

use MvcliteCore\Database\Exceptions\NegativeOrNullStackException;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcLite\Models\Festival;

/**
 * Query result pagination class.
 *
 * This class allows to split result array by stack
 * using specified maximum cardinality.
 *
 * @author belicfr
 */
class Pagination
{
    /** Number of lines per page. */
    private int $stacking;

    /** Pagination source. */
    private array $source;

    public function __construct(array $source, int $stacking)
    {
        $this->source = $source;
        $this->stacking = $stacking;
    }

    /**
     * @return int Current stacking
     */
    public function getStacking(): int
    {
        return $this->stacking;
    }

    /**
     * @param int $stacking New stacking
     * @return $this Current Pagination instance
     */
    public function setStacking(int $stacking): Pagination
    {
        if ($stacking <= 0)
        {
            $error = new NegativeOrNullStackException();
            $error->render();
        }

        $this->stacking = $stacking;

        return $this;
    }

    /**
     * @return array Used DatabaseQuery object
     */
    public function getSource(): array
    {
        return $this->source;
    }

    /**
     * @return array Pages array
     */
    public function getPages(): array
    {
        $stacking = $this->getStacking();
        $pages = [[]];

        foreach ($this->getSource() as $line)
        {
            if (count($pages[count($pages) - 1]) < $stacking)
            {
                $pages[count($pages) - 1][] = $line;
            }
            else
            {
                $pages[] = [];
            }
        }

        return $pages;
    }

    /**
     * @param int $page Page number
     * @return array|null Pages array if page exists;
     *                     else NULL
     */
    public function getPage(int $page): ?array
    {
        return $this->getPages()[$page - 1] ?? null;
    }

    /**
     * @return int Pages count
     */
    public function count(): int
    {
        return count($this->getPages());
    }
}