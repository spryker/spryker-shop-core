<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalStatusWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuoteApprovalStatusWidget\Dependency\Client\QuoteApprovalStatusWidgetToQuoteApprovalClientBridge;

class QuoteApprovalStatusWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE_APPROVAL = 'CLIENT_QUOTE_APPROVAL';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteApprovalClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteApprovalClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE_APPROVAL] = function (Container $container) {
            return new QuoteApprovalStatusWidgetToQuoteApprovalClientBridge(
                $container->getLocator()->quoteApproval()->client()
            );
        };

        return $container;
    }
}
