<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCartReorderClientInterface;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCustomerClientInterface;
use SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToZedRequestClientInterface;
use SprykerShop\Yves\CartReorderPage\Form\CartReorderForm;
use SprykerShop\Yves\CartReorderPage\Form\Handler\CartReorderHandler;
use SprykerShop\Yves\CartReorderPage\Form\Handler\CartReorderHandlerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\CartReorderPage\CartReorderPageConfig getConfig()
 */
class CartReorderPageFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCartReorderForm(): FormInterface
    {
        return $this->getFormFactory()->create(CartReorderForm::class);
    }

    /**
     * @return \SprykerShop\Yves\CartReorderPage\Form\Handler\CartReorderHandlerInterface
     */
    public function createCartReorderHandler(): CartReorderHandlerInterface
    {
        return new CartReorderHandler(
            $this->getCustomerClient(),
            $this->getCartReorderClient(),
            $this->getZedRequestClient(),
            $this->getCartReorderRequestExpanderPlugins(),
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
     * @return \SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCustomerClientInterface
     */
    public function getCustomerClient(): CartReorderPageToCustomerClientInterface
    {
        return $this->getProvidedDependency(CartReorderPageDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToZedRequestClientInterface
     */
    public function getZedRequestClient(): CartReorderPageToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CartReorderPageDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\CartReorderPage\Dependency\Client\CartReorderPageToCartReorderClientInterface
     */
    public function getCartReorderClient(): CartReorderPageToCartReorderClientInterface
    {
        return $this->getProvidedDependency(CartReorderPageDependencyProvider::CLIENT_CART_REORDER);
    }

    /**
     * @return list<\SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderItemCheckboxAttributeExpanderPluginInterface>
     */
    public function getCartReorderItemCheckboxAttributeExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderPageDependencyProvider::PLUGINS_CART_REORDER_ITEM_CHECKBOX_ATTRIBUTE_EXPANDER);
    }

    /**
     * @return list<\SprykerShop\Yves\CartReorderPageExtension\Dependency\Plugin\CartReorderRequestExpanderPluginInterface>
     */
    public function getCartReorderRequestExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartReorderPageDependencyProvider::PLUGINS_CART_REORDER_REQUEST_EXPANDER);
    }
}
