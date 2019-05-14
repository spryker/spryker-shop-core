<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCompanyUserClientBridge;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToCustomerClientBridge;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToPersistentCartClientBridge;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteClientBridge;
use SprykerShop\Yves\QuoteRequestWidget\Dependency\Client\QuoteRequestWidgetToQuoteRequestClientBridge;

class QuoteRequestWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const CLIENT_QUOTE_REQUEST = 'CLIENT_QUOTE_REQUEST';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addQuoteRequestClient($container);
        $container = $this->addPersistentCartClient($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCompanyUserClient(Container $container): Container
    {
        $container[static::CLIENT_COMPANY_USER] = function (Container $container) {
            return new QuoteRequestWidgetToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteRequestClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE_REQUEST] = function (Container $container) {
            return new QuoteRequestWidgetToQuoteRequestClientBridge($container->getLocator()->quoteRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new QuoteRequestWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPersistentCartClient(Container $container): Container
    {
        $container[static::CLIENT_PERSISTENT_CART] = function (Container $container) {
            return new QuoteRequestWidgetToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container[static::CLIENT_CUSTOMER] = function (Container $container) {
            return new QuoteRequestWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }
}
