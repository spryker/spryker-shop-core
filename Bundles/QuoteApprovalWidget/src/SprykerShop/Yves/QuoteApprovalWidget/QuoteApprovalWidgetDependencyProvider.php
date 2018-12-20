<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToCustomerClientBridge;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToGlossaryStorageClientBridge;
use SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client\QuoteApprovalWidgetToQuoteApprovalClientBridge;

class QuoteApprovalWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';
    public const CLIENT_QUOTE_APPROVAL = 'CLIENT_QUOTE_APPROVAL';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = parent::provideDependencies($container);
        $container = $this->addClientCustomer($container);
        $container = $this->addClientGlossaryStorage($container);
        $container = $this->addClientQuoteApproval($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addClientCustomer(Container $container)
    {
        $container[self::CLIENT_CUSTOMER] = function (Container $container) {
            return new QuoteApprovalWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addClientGlossaryStorage(Container $container)
    {
        $container[self::CLIENT_GLOSSARY_STORAGE] = function (Container $container) {
            return new QuoteApprovalWidgetToGlossaryStorageClientBridge($container->getLocator()->glossaryStorage()->client());
        };

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
