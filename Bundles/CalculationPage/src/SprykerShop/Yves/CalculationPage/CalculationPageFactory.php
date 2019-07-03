<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CalculationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToCalculationClientInterface;

/**
 * @method \Spryker\Client\Calculation\CalculationClientInterface getClient()
 * @method \SprykerShop\Yves\CalculationPage\CalculationPageConfig getConfig()
 */
class CalculationPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToQuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(CalculationPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToCalculationClientInterface
     */
    public function getCalculationClient(): CalculationPageToCalculationClientInterface
    {
        return $this->getProvidedDependency(CalculationPageDependencyProvider::CLIENT_CALCULATION);
    }
}
