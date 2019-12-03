<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Client\ProductSearchWidgetToCatalogClientInterface;
use SprykerShop\Yves\ProductSearchWidget\Dependency\Service\ProductSearchWidgetToUtilEncodingServiceInterface;
use SprykerShop\Yves\ProductSearchWidget\Form\ProductQuickAddForm;
use SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapper;
use SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface;
use SprykerShop\Yves\ProductSearchWidget\Reader\ProductConcreteReader;
use SprykerShop\Yves\ProductSearchWidget\Reader\ProductConcreteReaderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ProductSearchWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Reader\ProductConcreteReaderInterface
     */
    public function createProductConcreteReader(): ProductConcreteReaderInterface
    {
        return new ProductConcreteReader(
            $this->getCatalogClient(),
            $this->createProductConcreteMapper()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductSearchWidget\Mapper\ProductConcreteMapperInterface
     */
    public function createProductConcreteMapper(): ProductConcreteMapperInterface
    {
        return new ProductConcreteMapper();
    }

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
}
