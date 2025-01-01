<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: Context.php
 * Created:   2024-12-27 13:52:54
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

use PrestaShop\PrestaShop\Core\Localization\Locale;


class Context
{
    public ?Controller $controller;

    public ?Language $language;

    public ?Smarty $smarty;

    public function getCurrentLocale(): ?Locale
    {
        return new Locale();
    }
}
