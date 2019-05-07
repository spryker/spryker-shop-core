<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSearchWidget\Builder\MessageBuilder;
use SprykerShop\Yves\ProductSearchWidget\Builder\MessageBuilderInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductStorageClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Service\ProductSearchWidgetToUtilEncodingServiceInterface;
use SprykerShop\Yves\ProductSearchWidget\Form\ProductQuickAddForm;
use SprykerShop\Yves\ProductSearchWidget\Resolver\ProductConcreteResolver;
use SprykerShop\Yves\ProductSearchWidget\Resolver\ProductConcreteResolverInterface;
use SprykerShop\Yves\ProductSearchWidget\ViewCollector\ProductAdditionalDataViewCollector;
use SprykerShop\Yves\ProductSearchWidget\ViewCollector\ProductAdditionalDataViewCollectorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ProductSearchWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface
     */
    public function getCatalogClient(): ProductSearchWidgetToCatalogClientInterface
    {
        return $this->getProvidedDependency(ProductSearchWidgetDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getProductQuickAddForm(): FormInterface
    {
        return $this->getFormFactory()->create(ProductQuickAddForm::class);
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Resolver\ProductConcreteResolverInterface
     */
    public function createProductConcreteResolver(): ProductConcreteResolverInterface
    {
        return new ProductConcreteResolver($this->getProductStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Builder\MessageBuilderInterface
     */
    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder();
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\ViewCollector\ProductAdditionalDataViewCollectorInterface
     */
    public function createProductAdditionalDataViewCollector(): ProductAdditionalDataViewCollectorInterface
    {
        return new ProductAdditionalDataViewCollector(
            $this->getProductQuantityStorageClient()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Dependency\Service\ProductSearchWidgetToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductSearchWidgetToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductSearchWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): ProductSearchWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSearchWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductSearchWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductSearchWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }
}
