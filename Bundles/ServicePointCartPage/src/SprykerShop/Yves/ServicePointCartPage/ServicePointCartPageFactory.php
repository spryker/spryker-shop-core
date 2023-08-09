<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointCartPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Replacer\QuoteItemReplacer;
use SprykerShop\Yves\ServicePointCartPage\Replacer\QuoteItemReplacerInterface;

class ServicePointCartPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Replacer\QuoteItemReplacerInterface
     */
    public function createQuoteItemReplacer(): QuoteItemReplacerInterface
    {
        return new QuoteItemReplacer(
            $this->getServicePointCartClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface
     */
    public function getServicePointCartClient(): ServicePointCartPageToServicePointCartClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartPageDependencyProvider::CLIENT_SERVICE_POINT_CART);
    }
}
