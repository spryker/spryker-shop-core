<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CurrencyWidget\Dependency\Client\CurrencyWidgetToCurrencyClientInterface;

class CurrencyWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CurrencyWidget\Dependency\Client\CurrencyWidgetToCurrencyClientInterface
     */
    public function getCurrencyClient(): CurrencyWidgetToCurrencyClientInterface
    {
        return $this->getProvidedDependency(CurrencyWidgetDependencyProvider::CLIENT_CURRENCY);
    }
}
