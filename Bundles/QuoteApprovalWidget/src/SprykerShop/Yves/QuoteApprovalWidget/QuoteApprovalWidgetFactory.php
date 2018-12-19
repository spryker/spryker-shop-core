<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface;

class QuoteApprovalWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientInterface
     */
    public function getQuoteApprovalClient(): QuoteApprovalWidgetToQuoteApprovalClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalWidgetDependencyProvider::CLIENT_QUOTE_APPROVAL);
    }
}
