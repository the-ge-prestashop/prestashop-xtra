<?php declare(strict_types=1);

/**
 * Base trait containing helpers used by hook traits
 *
 * File name: ModuleXtraMethods.php
 * Created:   2025-01-03 10:29:21
 * @author    Gabriel Tenita <dev2023@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2025-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\Xtra\PrestaShop\Module;

use Context;
use Controller;
use PrestaShop\PrestaShop\Core\Localization\Locale;
use TheGe\Xtra\PrestaShop\Module\Exception\InvalidModuleAssetException;


trait ModuleXtraMethods
{
    /** @var Context */
    protected $context;

    /** @var \Smarty_Data|\Smarty_Internal_TemplateBase */
    protected $smarty;

    private ?Controller $activeController;

    private Locale $activeLocale;

    private function init(): void
    {
        // The ternary \Module::__construct() is using will fail for null
        $this->context ??= Context::getContext();
    }

    private function getActiveController(): ?Controller
    {
        return $this->activeController ??= $this->context->controller;
    }

    private function getActiveLocale(): Locale
    {
        return $this->activeLocale = $this->context->getCurrentLocale()
            ?? $this
                ->getContainer()
                ->get('prestashop.core.localization.locale.repository')
                ->getLocale($this->context->language?->getLocale());
    }

    /**
     * Get the hook traits list by filtering the module traits array by the
     * existence of both the 'Hook' trait name prefix and a method with the trait name,
     * then convert the list to a hook names list.
     *
     * @return string[] The PrestaShop hooks list
     */
    private function hooks(): array
    {
        return array_map(
            fn($v) => lcfirst(substr($v, 4)),
            array_filter(
                array_map(
                    fn($v) => substr(strrchr($v, '\\') ?: $v, 1),
                    array_keys(class_uses($this, false))),
                fn($v) => str_starts_with($v, 'Hook') && method_exists($this, $v)
            )
        );
    }

    private function getControllerKey(?object $controller = null): string
    {
        $controller ??= $this->activeController;
        $controller_class = $controller === null ? '' : $controller::class;

        return strtolower(str_replace('Controller', '', $controller_class));
    }

    /**
     * Adds a CSS or JavaScript file
     * https://devdocs.prestashop-project.org/8/modules/creation/displaying-content-in-front-office/
     *
     * @param string              $uri The CSS or Javascript URI relative to the asset root path, which are
     *                                 'views/css' and 'views/js' in the module folder; e.g. admin/form.js
     * @param array<string,mixed> $options
     */
    private function addViewAsset(string $uri, array $options = []): void
    {
        ['extension' => $extension, 'filename' => $filename] = pathinfo($uri) + ['extension' => null, 'filename' => null];
        $id  = "{$this->name}-$filename";
        // Asset URI e.g. '/shop/modules/mymodule/views/css/admin/somefile.css'
        $path = fn(bool $is_modern): string => ($is_modern ? "modules/{$this->name}" : $this->getPathUri()) . "/views/{$extension}/{$uri}";

        if ($this->activeController === null) {
            return;
        }

        /**
         * @param   array $method   [0 => legacy method name, 1 => modern method name]
         * @return  array           [0 => method name, 1 => false if legacy, true if modern] 
         */
        $findMethod = fn(array $method): array => [$method[(int) ($is_modern = method_exists($this->activeController, $method[1]))], $is_modern];
        switch ($extension) {
            case 'css':
                [$method, $is_modern] = $findMethod(['addCSS', 'registerStylesheet']);
                $args = $is_modern ? [$id, $path(true), $options] : [$path(false), $options['media'] ?? 'all'];
                break;
            case 'js':
                [$method, $is_modern] = $findMethod(['addJS', 'registerJavascript']);
                $args = $is_modern ? [$id, $path(true), $options] : [$path(false)];
                break;
            default:
                throw new InvalidModuleAssetException("Invalid extension for asset '{$uri}' of module '{$this->name}'");
        };

        $this->activeController->$method(...$args);
    }

    /**
     * Smarty template helper
     *
     * $this->moduleMainFile stores the main file __FILE__ constant
     * @see TheGePricesAltCurrency (thegepricesaltcurrency.php:52)
     *
     * @param      array<mixed>  $template_vars
     *
     * @return     string        The HTML code resulted by rendering the Smarty template
     */
    private function renderTemplate(string $template, array $template_vars): string // @phpstan-ignore method.unused
    {
        if ($this->smarty === null) {
            return '';
        }

        $this->smarty->assign($template_vars);

        return $this->display($this->moduleMainFile, $template);
    }

    private function logException(\Exception $exception, string $context): string
    {
        $this
            ->getContainer()
            ->get('@logger')
            ->logError("[{$exception->getFile()}:{$exception->getLine()}] {$exception->getMessage()}");
        $message = sprintf($this->trans('There was an error during %s.', [], "Modules.{$this->name}.Admin"), $context);
        $suffix  = $this->trans('Please, ask your developer to consult the logs or contact us through the Addons website.', [], "Modules.{$this->name}.Admin");

        return "{$message} {$suffix}";
    }
}
