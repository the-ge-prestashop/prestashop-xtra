<?php

namespace PrestaShop\PrestaShop\Adapter;


class SymfonyContainer
{
    public static function getInstance(): SymfonyContainer
    {
        return new self();
    }

    public function getParameter(string $name): string
    {
        return "{$name}.parameter.value";
    }
}
