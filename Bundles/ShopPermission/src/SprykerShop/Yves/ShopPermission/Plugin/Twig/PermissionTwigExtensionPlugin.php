<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopPermission\Plugin\Twig;

use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ShopPermission\ShopPermissionFactory getFactory()
 */
class PermissionTwigExtensionPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return static::class;
    }

    /**
     * @example for a twig template:
     *
     * {{if can('permission.allow.product.price') }}
     *      {% productAbstract.price %}
     * {{ endif }}
     *
     * @return array
     */
    public function getFunctions()
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
