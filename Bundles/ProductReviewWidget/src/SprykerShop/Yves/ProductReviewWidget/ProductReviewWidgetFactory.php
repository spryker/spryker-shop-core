<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductReviewWidget;

use SprykerShop\Yves\ProductReviewWidget\Controller\Calculator\ProductReviewSummaryCalculator;
use SprykerShop\Yves\ProductReviewWidget\Form\DataProvider\ProductReviewFormDataProvider;
use SprykerShop\Yves\ProductReviewWidget\Form\ProductReviewForm;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\ProductReview\ProductReviewFactory as SprykerProductReviewFactory;

class ProductReviewWidgetFactory extends SprykerProductReviewFactory
{
    /**
     * @return \Pyz\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Client\Product\ProductClientInterface
     */
    public function getProductClient()
    {
        return $this->getProvidedDependency(ProductReviewWidgetDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Client\ProductReview\ProductReviewClientInterface
     */
    public function getProductReviewClient()
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
            new ProductReviewForm($this->getProductReviewClient()),
            $dataProvider->getData($idProductAbstract),
            $dataProvider->getOptions()
        );

        return $form;
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
