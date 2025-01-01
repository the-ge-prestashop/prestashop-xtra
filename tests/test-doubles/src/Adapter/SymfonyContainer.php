<?php

/**
 * [TODO] add description
 *
 * File name: SymfonyContainer.php
 * Created:   2024-12-29 09:36:35
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace PrestaShop\PrestaShop\Adapter;

use Service;


final class SymfonyContainer
{
    public static function getInstance(): self
    {
        return new self();
    }

    public function get(string $id): Service
    {
        return new Service();
    }

    public function getParameter(string $id): string
    {
        return "mock.$id.parameter.value";
    }
}
