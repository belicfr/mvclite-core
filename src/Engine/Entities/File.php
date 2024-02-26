<?php

namespace MvcliteCore\Engine\Entities;

use MvcliteCore\Engine\DevelopmentUtilities\Debug;

/**
 * Class that represents a file.
 *
 * @author belicfr
 */
class File
{
    /** File name. */
    private string $name;

    /** File full path. */
    private string $fullPath;

    /** File type. */
    private string $type;

    /** File temporary name. */
    private string $temporaryName;

    /** File upload errors. */
    private int $error;

    /** File size. */
    private int $size;

    public function __construct(string $name,
                                string $fullPath,
                                string $type,
                                string $temporaryName,
                                int $error,
                                int $size)
    {
        $this->name = $name;
        $this->fullPath = $fullPath;
        $this->type = $type;
        $this->temporaryName = $temporaryName;
        $this->error = $error;
        $this->size = $size;
    }

    /**
     * @return string File name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string File full path
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * @return string File type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string File temporary name
     */
    public function getTemporaryName(): string
    {
        return $this->temporaryName;
    }

    /**
     * @return int File upload errors
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @return int File size
     */
    public function getSize(): int
    {
        return $this->size;
    }

    public function getContent(): string
    {
        return file_get_contents($this->getTemporaryName());
    }

    /**
     * @param string $name New file name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isImage(): bool
    {
        if ($this->getError() !== UPLOAD_ERR_OK)
        {
            return false;
        }

        $contentType = mime_content_type($this->getTemporaryName());
        return $contentType && explode('/', $contentType)[0] == "image";
    }

    public function asImage(): ?Image
    {
        if (!$this->isImage())
        {
            return null;
        }

        return new Image(
            $this->getName(), $this->getFullPath(), $this->getType(),
            $this->getTemporaryName(), $this->getError(), $this->getSize()
        );
    }
}