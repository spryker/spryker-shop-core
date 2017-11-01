<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CalculationPage;

use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\Calculation\CalculationClientInterface getClient()
 */
class CalculationPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Quote\QuoteClientInterface
     */
    public function getQuoteClient()
    {
        return $this->getProvidedDependency(CalculationPageDependencyProvider::CLIENT_QUOTE);
    }
    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(CalculationPageDependencyProvider::CLIENT_CALCULATION);
    }
}
