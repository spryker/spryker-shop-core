<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductReviewWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\ProductReview\ProductReviewFactory as SprykerProductReviewFactory;
use SprykerShop\Yves\ProductReviewWidget\Controller\Calculator\ProductReviewSummaryCalculator;
use SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToCustomerClientInterface;
use SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductClientInterface;
use SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductReviewClientInterface;
use SprykerShop\Yves\ProductReviewWidget\Form\DataProvider\ProductReviewFormDataProvider;
use SprykerShop\Yves\ProductReviewWidget\Form\ProductReviewForm;

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
     * @return \SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductClientInterface
     */
    public function getProductClient(): ProductReviewWidgetToProductClientInterface
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Dependency\Client\ProductReviewWidgetToProductReviewClientInterface
     */
    public function getProductReviewClient(): ProductReviewWidgetToProductReviewClientInterface
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_PRODUCT_REVIEW);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
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
            $this->createProductReviewFormType(),
            $dataProvider->getData($idProductAbstract),
            $dataProvider->getOptions()
        );

        return $form;
    }

    /**
     * @return \Symfony\Component\Form\AbstractType
     */
    protected function createProductReviewFormType()
    {
        return new ProductReviewForm();
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Controller\Calculator\ProductReviewSummaryCalculatorInterface
     */
    public function createProductReviewSummaryCalculator()
    {
        return new ProductReviewSummaryCalculator($this->getProductReviewClient());
    }

    /**
     * @return \SprykerShop\Yves\ProductReviewWidget\Form\DataProvider\ProductReviewFormDataProvider
     */
    protected function createProductReviewFormDataProvider()
    {
        return new ProductReviewFormDataProvider();
    }
}
