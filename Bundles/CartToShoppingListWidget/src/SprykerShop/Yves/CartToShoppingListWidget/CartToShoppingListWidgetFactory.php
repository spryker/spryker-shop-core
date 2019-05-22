<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartToShoppingListWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartToShoppingListWidget\Dependency\Client\CartToShoppingListWidgetToCustomerClientInterface;
use SprykerShop\Yves\CartToShoppingListWidget\Dependency\Client\CartToShoppingListWidgetToShoppingListClientInterface;
use SprykerShop\Yves\CartToShoppingListWidget\Form\DataProvider\ShoppingListFromCartFormDataProvider;
use SprykerShop\Yves\CartToShoppingListWidget\Form\ShoppingListFromCartForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CartToShoppingListWidget\CartToShoppingListWidgetConfig getConfig()
 */
class CartToShoppingListWidgetFactory extends AbstractFactory
{
    /**
     * @param int|null $idQuote
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartFromShoppingListForm(?int $idQuote): FormInterface
    {
        $formDataProvider = $this->createCartFromShoppingListFormDataProvider();

        return $this->getFormFactory()->create(ShoppingListFromCartForm::class, $formDataProvider->getData($idQuote), $formDataProvider->getOptions());
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \SprykerShop\Yves\CartToShoppingListWidget\Form\DataProvider\ShoppingListFromCartFormDataProvider
     */
    public function createCartFromShoppingListFormDataProvider(): ShoppingListFromCartFormDataProvider
    {
        return new ShoppingListFromCartFormDataProvider($this->getShoppingListClient());
    }

    /**
     * @return \SprykerShop\Yves\CartToShoppingListWidget\Dependency\Client\CartToShoppingListWidgetToShoppingListClientInterface
     */
    public function getShoppingListClient(): CartToShoppingListWidgetToShoppingListClientInterface
    {
        return $this->getProvidedDependency(CartToShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return Dependency\Client\CartToShoppingListWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CartToShoppingListWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CartToShoppingListWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
