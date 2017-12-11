<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CalculationPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CalculationPage\Dependency\Client\CalculationPageToCalculationClientInterface;

/**
 * @method \Spryker\Client\Calculation\CalculationClientInterface getClient()
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
