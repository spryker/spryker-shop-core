<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCancelWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToCustomerClientInterface;
use SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToSalesClientInterface;
use SprykerShop\Yves\OrderCancelWidget\Form\OrderCancelForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\OrderCancelWidget\OrderCancelWidgetConfig getConfig()
 */
class OrderCancelWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderCancelForm(): FormInterface
    {
        return $this->getFormFactory()->create(OrderCancelForm::class);
    }

    /**
     * @return \SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): OrderCancelWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(OrderCancelWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\OrderCancelWidget\Dependency\Client\OrderCancelWidgetToSalesClientInterface
     */
    public function getSalesClient(): OrderCancelWidgetToSalesClientInterface
    {
        return $this->getProvidedDependency(OrderCancelWidgetDependencyProvider::CLIENT_SALES);
    }
}
