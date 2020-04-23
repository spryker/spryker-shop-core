<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface;
use SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider;
use SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandler;
use SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandlerInterface;
use SprykerShop\Yves\SalesReturnPage\Form\Listener\ReturnItemsFormEventsListener;
use SprykerShop\Yves\SalesReturnPage\Form\Listener\ReturnItemsFormEventsListenerInterface;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\SalesReturnPage\SalesReturnPageConfig getConfig()
 */
class SalesReturnPageFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCreateReturnForm(OrderTransfer $orderTransfer): FormInterface
    {
        $returnCreateFormDataProvider = $this->createReturnCreateFormDataProvider();

        return $this->getFormFactory()->create(
            ReturnCreateForm::class,
            $returnCreateFormDataProvider->getData($orderTransfer),
            $returnCreateFormDataProvider->getOptions()
        );
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Form\Listener\ReturnItemsFormEventsListenerInterface
     */
    public function createReturnItemsFormEventsListener(): ReturnItemsFormEventsListenerInterface
    {
        return new ReturnItemsFormEventsListener();
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandlerInterface
     */
    public function createReturnHandler(): ReturnHandlerInterface
    {
        return new ReturnHandler(
            $this->getSalesReturnClient(),
            $this->getCustomerClient(),
            $this->getStoreClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider
     */
    public function createReturnCreateFormDataProvider(): ReturnCreateFormDataProvider
    {
        return new ReturnCreateFormDataProvider(
            $this->getSalesReturnClient()
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientInterface
     */
    public function getSalesReturnClient(): SalesReturnPageToSalesReturnClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_SALES_RETURN);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesClientInterface
     */
    public function getSalesClient(): SalesReturnPageToSalesClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientInterface
     */
    public function getCustomerClient(): SalesReturnPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface
     */
    public function getStoreClient(): SalesReturnPageToStoreClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_STORE);
    }
}
