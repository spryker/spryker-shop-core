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
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnSearchClientInterface;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface;
use SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider;
use SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandler;
use SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandlerInterface;
use SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm;
use SprykerShop\Yves\SalesReturnPage\Reader\ReturnReader;
use SprykerShop\Yves\SalesReturnPage\Reader\ReturnReaderInterface;
use SprykerShop\Yves\SalesReturnPage\Sanitizer\ItemSanitizer;
use SprykerShop\Yves\SalesReturnPage\Sanitizer\ItemSanitizerInterface;
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
            $returnCreateFormDataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandlerInterface
     */
    public function createReturnHandler(): ReturnHandlerInterface
    {
        return new ReturnHandler(
            $this->getSalesReturnClient(),
            $this->getCustomerClient(),
            $this->getStoreClient(),
            $this->getReturnCreateFormHandlerPlugins(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Form\DataProvider\ReturnCreateFormDataProvider
     */
    public function createReturnCreateFormDataProvider(): ReturnCreateFormDataProvider
    {
        return new ReturnCreateFormDataProvider(
            $this->getSalesReturnClient(),
            $this->getSalesReturnSearchClient(),
            $this->getReturnCreateFormHandlerPlugins(),
        );
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Reader\ReturnReaderInterface
     */
    public function createReturnReader(): ReturnReaderInterface
    {
        return new ReturnReader($this->getSalesReturnClient());
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return array<\SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface>
     */
    public function getReturnCreateFormHandlerPlugins(): array
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::PLUGINS_RETURN_CREATE_FORM_HANDLER);
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
     * @return \SprykerShop\Yves\SalesReturnPage\SalesReturnPageConfig
     */
    public function getModuleConfig(): SalesReturnPageConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToStoreClientInterface
     */
    public function getStoreClient(): SalesReturnPageToStoreClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnSearchClientInterface
     */
    public function getSalesReturnSearchClient(): SalesReturnPageToSalesReturnSearchClientInterface
    {
        return $this->getProvidedDependency(SalesReturnPageDependencyProvider::CLIENT_SALES_RETURN_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\SalesReturnPage\Sanitizer\ItemSanitizerInterface
     */
    public function createItemSanitizer(): ItemSanitizerInterface
    {
        return new ItemSanitizer();
    }
}
