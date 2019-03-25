<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopPermission\Plugin\Twig;

use Silex\Application;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;
use Twig\TwigFunction;

/**
 * @deprecated Please use PermissionTwigExtensionPlugin instead.
 *
 * @method \SprykerShop\Yves\ShopPermission\ShopPermissionFactory getFactory()
 */
class PermissionTwigFunctionPlugin extends AbstractPlugin implements TwigFunctionPluginInterface
{
    /**
     * @example for a twig template
     * {{if can('permission.allow.product.price') }}
     *      {% productAbstract.price %}
     * {{ endif }}
     *
     * @param \Silex\Application $application
     *
     * @return array
     */
    public function getFunctions(Application $application)
    {
        return [
            new TwigFunction('can', [
                $this,
                'can',
            ], [
                'needs_context' => false,
                'needs_environment' => false,
            ]),
        ];
    }

    /**
     * @param string $permissionKey
     * @param string|int|mixed|null $context
     *
     * @return bool
     */
    public function can($permissionKey, $context = null)
    {
        return $this->getFactory()
            ->getPermissionClient()
            ->can($permissionKey, $context);
    }
}
