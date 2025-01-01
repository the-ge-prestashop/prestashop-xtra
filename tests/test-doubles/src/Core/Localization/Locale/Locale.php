<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: Repository.php
 * Created:   2025-01-01 20:10:49
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2025-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace PrestaShop\PrestaShop\Core\Localization\Locale;

use PrestaShop\PrestaShop\Core\Localization\Locale;


class Repository
{
    public function getLocale(string $locale_iso): Locale
    {
        return new Locale($locale_iso);
    }
}
