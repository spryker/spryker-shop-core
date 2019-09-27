<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Plugin\Provider;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Application\Plugin\Provider\YvesControllerProvider as SprykerYvesControllerProvider;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 *
 * @deprecated Use `\Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin` instead.
 */
abstract class AbstractYvesControllerProvider extends SprykerYvesControllerProvider
{
    /**
     * @var string
     */
    protected $allowedLocalesPattern;

    /**
     * @return string
     */
    public function getAllowedLocalesPattern()
    {
        if ($this->allowedLocalesPattern !== null) {
            return $this->allowedLocalesPattern;
        }

        $systemLocales = Store::getInstance()->getLocales();
        $implodedLocales = implode('|', array_keys($systemLocales));
        $this->allowedLocalesPattern = '(' . $implodedLocales . ')\/';

        return $this->allowedLocalesPattern;
    }
}
