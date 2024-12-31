<?php declare(strict_types=1);

/**
 * Base trait containing helpers used by hook traits
 *
 * File name: ModuleSharedMethods.php
 * Created:   2024-09-02 07:24
 * @author    Gabriel Tenita <the.ge.1447624801@tenita.eu>
 * @link      https://github.com/the-ge/
 * @copyright Copyright (c) 2024-present Gabriel Tenita
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License version 2.0
 */

namespace TheGe\Xtra\PrestaShop\Module;

use TheGe\Xtra\PrestaShop\Module\Exception\InvalidModuleAssetException;


trait ModuleSharedMethods
{

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
        $controller ??= $this->context->controller;
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
        $controller = $this->context->controller;

        if ($controller === null) {
            return;
        }

        /**
         * @param   array $method   [0 => legacy method name, 1 => modern method name]
         * @return  array           [0 => method name, 1 => false if legacy, true if modern] 
         */
        $findMethod = fn(array $method): array => [$method[(int) ($is_modern = method_exists($controller, $method[1]))], $is_modern];
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

        $controller->$method(...$args);
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
        $smarty = $this->context->smarty;

        if ($smarty === null) {
            return '';
        }

        $smarty->assign($template_vars);

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
