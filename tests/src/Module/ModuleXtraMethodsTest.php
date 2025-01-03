<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: ModuleSharedMethodsTest.php
 * Created:   2025-01-03 11:04:18
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2025-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\Test\Test\Xtra\PrestaShop\Module;

require_once __DIR__ . '/../../../vendor/autoload.php';

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use TheGe\Xtra\PrestaShop\Module\ModuleXtraMethods;


abstract class ModuleXtraMethodsTest
{
    use ModuleXtraMethods;

    public \Context $context;

    public string $name = 'moduleXtramethodstest';

    private string $moduleMainFile = __FILE__;

    public function get(string $key): object
    {
        return new class {
            public function logError(string $message): void {}
        };
    }

    public function getPathUri(): string
    {
        return 'modules/this_module';
    }

    public function getContainer(): SymfonyContainer
    {
        return SymfonyContainer::getInstance();
    }

    public function display(string $self_path, string $template): string
    {
        return '[rendered Smarty template]';
    }

    /** @param array<string,string> $replacements */
    public function trans(string $source, array $replacements, string $key): string
    {
        return "[translated string]";
    }
}
