<?php declare(strict_types=1);

/**
 * [TODO] add description
 *
 * File name: AbstractPrestashopModule.php
 * Created:   2024-12-26 01:47:07
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\PrestaShop\XtraTest\Module;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Context;
use TheGe\PrestaShop\Xtra\Module\ModuleSharedMethods;


abstract class ModuleSharedMethodsTest
{
    use ModuleSharedMethods;

    public Context $context;

    public string $name = 'modulesharedmethodstest';

    private string $moduleMainFile = __FILE__;

    public function get(string $key): object
    {
        return new class{};
    }

    public function getPathUri(): string
    {
        return 'modules/this_module';
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
