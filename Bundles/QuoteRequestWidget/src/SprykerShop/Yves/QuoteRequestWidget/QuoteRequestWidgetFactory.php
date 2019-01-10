<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetConfig getConfig()
 */
class QuoteRequestWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteRequestWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteRequestWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientInterface
     */
    public function getQuoteRequestClient(): QuoteRequestWidgetToQuoteRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteRequestWidgetDependencyProvider::CLIENT_QUOTE_REQUEST);
    }
}
