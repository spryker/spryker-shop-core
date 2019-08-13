<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin;

use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationFactory getFactory()
 * @method \SprykerShop\Yves\ShopApplication\ShopApplicationConfig getConfig()
 */
class ShopApplicationTwigExtensionPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return static::class;
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
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
        return true;
    }

    /**
     * @return \Twig\TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('floor', function ($value) {
                return floor($value);
            }),
            new TwigFilter('ceil', function ($value) {
                return ceil($value);
            }),
            new TwigFilter('int', function ($value) {
                return (int)$value;
            }),
        ];
    }

    /**
     * @return string[]
     */
    public function getGlobals(): array
    {
        return [
            'environment' => $this->getConfig()->getTwigEnvironmentName(),
        ];
    }
}
