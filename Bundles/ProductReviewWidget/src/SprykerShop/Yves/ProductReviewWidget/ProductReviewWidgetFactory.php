<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\Application;
use Spryker\Yves\ProductReview\ProductReviewFactory as SprykerProductReviewFactory;
use SprykerShop\Yves\ProductReviewWidget\BulkProductReviewSearchRequestBuilder\BulkProductReviewSearchRequestBuilder;
use SprykerShop\Yves\ProductReviewWidget\BulkProductReviewSearchRequestBuilder\BulkProductReviewSearchRequestBuilderInterface;
use SprykerShop\Yves\ProductReviewWidget\Controller\Calculator\ProductReviewSummaryCalculator;
use SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToCustomerClientInterface;
use SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductReviewClientInterface;
use SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductReviewStorageClientInterface;
use SprykerShop\Yves\ProductReviewWidget\Form\DataProvider\ProductReviewFormDataProvider;
use SprykerShop\Yves\ProductReviewWidget\Form\ProductReviewForm;
use SprykerShop\Yves\ProductReviewWidget\ProductReviewSearchRequestBuilder\ProductReviewSearchRequestBuilder;
use SprykerShop\Yves\ProductReviewWidget\ProductReviewSearchRequestBuilder\ProductReviewSearchRequestBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductReviewWidget\ProductReviewWidgetConfig getConfig()
 */
class ProductReviewWidgetFactory extends SprykerProductReviewFactory
{
    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): ProductReviewWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductReviewClientInterface
     */
    public function getProductReviewClient(): ProductReviewWidgetToProductReviewClientInterface
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_PRODUCT_REVIEW);
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductReviewStorageClientInterface
     */
    public function getProductReviewStorageClient(): ProductReviewWidgetToProductReviewStorageClientInterface
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_PRODUCT_REVIEW_STORAGE);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory()
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createProductReviewForm($idProductAbstract)
    {
        $dataProvider = $this->createProductReviewFormDataProvider();
        $form = $this->getFormFactory()->create(
            ProductReviewForm::class,
            $dataProvider->getData($idProductAbstract),
            $dataProvider->getOptions()
        );

        return $form;
    }

    /**
     * @deprecated Use {@link \Spryker\Client\ProductReview\ProductReviewFactory::createProductReviewSummaryCalculator()} instead.
     *
     * @return \SprykerShop\Yves\ProductReviewWidget\Controller\Calculator\ProductReviewSummaryCalculatorInterface
     */
    public function createProductReviewSummaryCalculator()
    {
        return new ProductReviewSummaryCalculator($this->getProductReviewClient());
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Form\DataProvider\ProductReviewFormDataProvider
     */
    public function createProductReviewFormDataProvider()
    {
        return new ProductReviewFormDataProvider();
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication(): Application
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getApplicationRequest(): Request
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::SERVICE_REQUEST_STACK)->getCurrentRequest();
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\ProductReviewSearchRequestBuilder\ProductReviewSearchRequestBuilderInterface
     */
    public function createProductReviewSearchRequestBuilder(): ProductReviewSearchRequestBuilderInterface
    {
        return new ProductReviewSearchRequestBuilder();
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\BulkProductReviewSearchRequestBuilder\BulkProductReviewSearchRequestBuilderInterface
     */
    public function createBulkProductReviewSearchRequestBuilder(): BulkProductReviewSearchRequestBuilderInterface
    {
        return new BulkProductReviewSearchRequestBuilder();
    }
}
