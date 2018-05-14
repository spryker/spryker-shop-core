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
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToProductStorageClientInterface;
use SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListPage\Form\Constraint\ShareShoppingListRequiredIdConstraint;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShareShoppingListDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShoppingListFormDataProvider;
use SprykerShop\Yves\ShoppingListPage\Form\Handler\AddToCartFormHandler;
use SprykerShop\Yves\ShoppingListPage\Form\Handler\AddToCartFormHandlerInterface;
use SprykerShop\Yves\ShoppingListPage\Form\ShareShoppingListForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListForm;
use SprykerShop\Yves\ShoppingListPage\Form\ShoppingListUpdateForm;
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
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShoppingListForm(?ShoppingListTransfer $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListForm::class, $data, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface|\SprykerShop\Yves\ShoppingListPage\Form\ShoppingListForm
     */
    public function getShoppingListUpdateForm(ShoppingListTransfer $data, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListUpdateForm::class, $data, $options);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShoppingListFormDataProvider
     */
    public function createShoppingListFormDataProvider(): ShoppingListFormDataProvider
    {
        return new ShoppingListFormDataProvider($this->getShoppingListClient(), $this->getCustomerClient());
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
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
     * @return \SprykerShop\Yves\ShoppingListPage\Form\Handler\AddToCartFormHandlerInterface
     */
    public function createAddToCartFormHandler(): AddToCartFormHandlerInterface
    {
        return new AddToCartFormHandler();
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

    /**
     * @param int $idShoppingList
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShareShoppingListForm(int $idShoppingList): FormInterface
    {
        $shareShoppingListFormDataProvider = $this->createShareShoppingListFormDataProvider();

        return $this->getFormFactory()->create(
            ShareShoppingListForm::class,
            $shareShoppingListFormDataProvider->getData($idShoppingList),
            $shareShoppingListFormDataProvider->getOptions()
        );
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Form\DataProvider\ShareShoppingListDataProvider
     */
    public function createShareShoppingListFormDataProvider(): ShareShoppingListDataProvider
    {
        return new ShareShoppingListDataProvider(
            $this->getCompanyBusinessUnitClient(),
            $this->getCompanyUserClient(),
            $this->getCustomerClient(),
            $this->getShoppingListClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyBusinessUnitClientInterface
     */
    public function getCompanyBusinessUnitClient(): ShoppingListPageToCompanyBusinessUnitClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Dependency\Client\ShoppingListPageToCompanyUserClientInterface
     */
    public function getCompanyUserClient(): ShoppingListPageToCompanyUserClientInterface
    {
        return $this->getProvidedDependency(ShoppingListPageDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListPage\Form\Constraint\ShareShoppingListRequiredIdConstraint
     */
    public function createShareShoppingListRequiredIdConstraint(): ShareShoppingListRequiredIdConstraint
    {
        return new ShareShoppingListRequiredIdConstraint();
    }
}
