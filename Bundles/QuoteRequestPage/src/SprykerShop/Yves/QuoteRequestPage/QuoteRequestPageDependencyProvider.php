<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCartClientBridge;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCompanyUserClientBridge;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToCustomerClientBridge;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToPersistentCartClientBridge;
use SprykerShop\Yves\QuoteRequestPage\Dependency\Client\QuoteRequestPageToQuoteRequestClientBridge;

class QuoteRequestPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_COMPANY_USER = 'CLIENT_COMPANY_USER';
    public const CLIENT_QUOTE_REQUEST = 'CLIENT_QUOTE_REQUEST';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_PERSISTENT_CART = 'CLIENT_PERSISTENT_CART';
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    public const PLUGINS_QUOTE_REQUEST_FORM_METADATA_FIELD = 'PLUGINS_QUOTE_REQUEST_FORM_METADATA_FIELD';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCompanyUserClient($container);
        $container = $this->addQuoteRequestClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addPersistentCartClient($container);
        $container = $this->addCustomerClient($container);

        $container = $this->addQuoteRequestFormMetadataFieldPlugins($container);

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
            return new QuoteRequestPageToCompanyUserClientBridge($container->getLocator()->companyUser()->client());
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
            return new QuoteRequestPageToQuoteRequestClientBridge($container->getLocator()->quoteRequest()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new QuoteRequestPageToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteRequestFormMetadataFieldPlugins(Container $container): Container
    {
        $container[static::PLUGINS_QUOTE_REQUEST_FORM_METADATA_FIELD] = function () {
            return $this->getQuoteRequestFormMetadataFieldPlugins();
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
            return new QuoteRequestPageToPersistentCartClientBridge($container->getLocator()->persistentCart()->client());
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
            return new QuoteRequestPageToCustomerClientBridge($container->getLocator()->customer()->client());
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface[]
     */
    protected function getQuoteRequestFormMetadataFieldPlugins(): array
    {
        return [];
    }
}
