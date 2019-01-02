<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalStatusWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuoteApprovalStatusWidget\Dependency\Client\QuoteApprovalStatusWidgetToQuoteApprovalClientInterface;

class QuoteApprovalStatusWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuoteApprovalStatusWidget\Dependency\Client\QuoteApprovalStatusWidgetToQuoteApprovalClientInterface
     */
    public function getQuoteApprovalClient(): QuoteApprovalStatusWidgetToQuoteApprovalClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalStatusWidgetDependencyProvider::CLIENT_QUOTE_APPROVAL);
    }
}
