<?php

namespace MvcliteCore\Plugins;

class Plugin
{
    private string $name;

    /**
     * @return string Plugin name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name New plugin name
     */
    protected function setName(string $name): void
    {
        $this->name = $name;
    }
}