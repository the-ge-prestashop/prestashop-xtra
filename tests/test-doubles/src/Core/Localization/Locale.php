<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: Locale.php
 * Created:   2025-01-01 19:37:29
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2025-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace PrestaShop\PrestaShop\Core\Localization;


class Locale
{
    public function __construct(
        private ?string $locale_iso = null // @phpstan-ignore property.onlyWritten
    ) {
    }
}
