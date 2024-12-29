<?php

/**
 * [TODO] add description
 *
 * File name: SymfonyContainerStub.php
 * Created:   2024-12-29 09:36:35
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\Test\TestDoubles\Xtra\PrestaShop;


final class SymfonyContainerStub
{
    public static function getInstance(): self
    {
        return new self();
    }

    public function get(string $id): PrestaShopLoggerStub
    {
        return new PrestaShopLoggerStub();
    }

    public function getParameter(string $id): string
    {
        return "mock.$id.parameter.value";
    }
}
