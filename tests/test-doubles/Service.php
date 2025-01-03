<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: Service.php
 * Created:   2024-12-29 10:04:15
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

use PrestaShop\PrestaShop\Core\Localization\Locale;


class Service
{
    public function logError(string $message): void
    {
    }

    public function getLocale(?string $locale_iso): Locale
    {
        return new Locale();
    }
}
