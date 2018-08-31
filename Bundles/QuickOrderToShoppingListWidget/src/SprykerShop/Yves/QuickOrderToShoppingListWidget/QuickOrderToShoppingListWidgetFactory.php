<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderToShoppingListWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuickOrderToShoppingListWidget\Dependency\Client\QuickOrderToShoppingListWidgetToCustomerClientInterface;
use SprykerShop\Yves\QuickOrderToShoppingListWidget\Dependency\Client\QuickOrderToShoppingListWidgetToShoppingListClientInterface;
use SprykerShop\Yves\QuickOrderToShoppingListWidget\Form\ShoppingListFromQuickOrderForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderToShoppingListWidget\QuickOrderToShoppingListWidgetConfig getConfig()
 */
class QuickOrderToShoppingListWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuickOrderToShoppingListWidget\Dependency\Client\QuickOrderToShoppingListWidgetToShoppingListClientInterface
     */
    public function getShoppingListClient(): QuickOrderToShoppingListWidgetToShoppingListClientInterface
    {
        return $this->getProvidedDependency(QuickOrderToShoppingListWidgetDependencyProvider::CLIENT_SHOPPING_LIST);
    }

    /**
     * @return Dependency\Client\QuickOrderToShoppingListWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): QuickOrderToShoppingListWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(QuickOrderToShoppingListWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getShoppingListFromQuickOrderForm(): FormInterface
    {
        return $this->getFormFactory()->create(ShoppingListFromQuickOrderForm::class, null, []);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
