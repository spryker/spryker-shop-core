<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage;

use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShoppingListPage\Business\AddToCartHandler;
use SprykerShop\Yves\ShoppingListPage\Business\AddToCartHandlerInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListPage\Form\AddAvailableProductsToCartForm;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\AddAvailableProductsToCartFormDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShoppingListFormDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig getConfig()
 */
class ShoppingListPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface
     */
    public function getCustomerClient(): ShoppingListPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer|null $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface|\SprykerShop\Yves\ShoppingListPage\Form\ShoppingListForm
     */
    public function getShoppingListForm(ShoppingListTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListForm::class, $data, $options);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShoppingListFormDataProvider
     */
    public function createShoppingListFormDataProvider(): ShoppingListFormDataProvider
    {
        return new ShoppingListFormDataProvider($this->getShoppingListClient(), $this->getCustomerClient());
    }

    /**
     * @param array $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface|\SprykerShop\Yves\ShoppingListPage\Form\AddAvailableProductsToCartForm
     */
    public function getAddAvailableProductsToCartForm(array $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(AddAvailableProductsToCartForm::class, $data, $options);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Form\DataProvider\AddAvailableProductsToCartFormDataProvider
     */
    public function createAddAvailableProductsToCartFormDataProvider(): AddAvailableProductsToCartFormDataProvider
    {
        return new AddAvailableProductsToCartFormDataProvider();
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Business\AddToCartHandlerInterface
     */
    public function createAddToCartHandler(): AddToCartHandlerInterface
    {
        return new AddToCartHandler($this->getShoppingListClient(), $this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface
     */
    public function getShoppingListClient(): ShoppingListPageToShoppingListClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): ShoppingListPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    public function getShoppingListItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::PLUGIN_SHOPPING_LIST_ITEM_EXPANDERS);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\ShoppingListPageConfig
     */
    public function getBundleConfig(): ShoppingListPageConfig
    {
        return $this->getConfig();
    }
}
