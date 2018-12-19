<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientBridge;

class QuoteApprovalWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE_APPROVAL = 'CLIENT_QUOTE_APPROVAL';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addClientQuoteApproval($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addClientQuoteApproval(Container $container)
    {
        $container[self::CLIENT_QUOTE_APPROVAL] = function (Container $container) {
            return new QuoteApprovalWidgetToQuoteApprovalClientBridge($container->getLocator()->quoteApproval()->client());
        };

        return $container;
    }
}
