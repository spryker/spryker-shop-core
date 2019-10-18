<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\CalculationPage\Plugin\Router\CalculationPageRouteProviderPlugin` instead.
 */
class CalculationPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_CALCULATION_DEBUG = 'calculation-debug';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addCalculationDebugRoute();
    }

    /**
     * @return $this
     */
    protected function addCalculationDebugRoute()
    {
        $this->createController('/calculation/debug', self::ROUTE_CALCULATION_DEBUG, 'CalculationPage', 'Debug', 'cart')
            ->method('GET');

        return $this;
    }
}
