<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListSessionClientInterface;
use SprykerShop\Yves\ShoppingListWidget\Form\DataProvider\ShoppingListFromCartFormDataProvider;
use SprykerShop\Yves\ShoppingListWidget\Form\FormHandler\CreateFromCartHandler;
use SprykerShop\Yves\ShoppingListWidget\Form\FormHandler\CreateFromCartHandlerInterface;
use SprykerShop\Yves\ShoppingListWidget\Form\ShoppingListFromCartForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetConfig getConfig()
 */
class ShoppingListWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Form\DataProvider\ShoppingListFromCartFormDataProvider
     */
    public function createShoppingListFromCartFormDataProvider(): ShoppingListFromCartFormDataProvider
    {
        return new ShoppingListFromCartFormDataProvider($this->getShoppingListClient());
    }

    /**
     * @param int|null $idQuote
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShoppingListFromCartForm(?int $idQuote): FormInterface
    {
        $formDataProvider = $this->createShoppingListFromCartFormDataProvider();

        return $this->getFormFactory()
            ->create(
                ShoppingListFromCartForm::class,
                $formDataProvider->getData($idQuote),
                $formDataProvider->getOptions()
            );
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Form\FormHandler\CreateFromCartHandlerInterface
     */
    public function createCreateFromCartHandler(): CreateFromCartHandlerInterface
    {
        return new CreateFromCartHandler($this->getShoppingListClient(), $this->getCustomerClient());
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListClientInterface
     */
    public function getShoppingListClient(): ShoppingListWidgetToShoppingListClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): ShoppingListWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\ShoppingListWidget\Dependency\Client\ShoppingListWidgetToShoppingListSessionClientInterface
     */
    public function getShoppingListSessionClient(): ShoppingListWidgetToShoppingListSessionClientInterface
    {
        return $this->getProvidedDependency(ShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST_SESSION);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
