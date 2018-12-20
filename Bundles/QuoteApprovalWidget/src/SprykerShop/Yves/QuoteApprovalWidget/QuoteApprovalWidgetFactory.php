<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;

class QuoteApprovalWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): QuoteApprovalWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): QuoteApprovalWidgetToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface
     */
    public function getQuoteApprovalClient(): QuoteApprovalWidgetToQuoteApprovalClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_QUOTE_APPROVAL);
    }
}
