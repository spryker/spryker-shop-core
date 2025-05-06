<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCartReorderClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCustomerClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToQuoteClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToSalesOrderAmendmentClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToZedRequestClientInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Form\CancelOrderAmendmentForm;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Form\Handler\OrderAmendmentHandler;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Form\Handler\OrderAmendmentHandlerInterface;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Form\OrderAmendmentForm;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 */
class SalesOrderAmendmentWidgetFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderAmendmentForm(): FormInterface
    {
        return $this->getFormFactory()->create(OrderAmendmentForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCancelOrderAmendmentForm(): FormInterface
    {
        return $this->getFormFactory()->create(CancelOrderAmendmentForm::class);
    }

    /**
     * @return \SprykerShop\Yves\SalesOrderAmendmentWidget\Form\Handler\OrderAmendmentHandlerInterface
     */
    public function createOrderAmendmentHandler(): OrderAmendmentHandlerInterface
    {
        return new OrderAmendmentHandler(
            $this->getCustomerClient(),
            $this->getCartReorderClient(),
            $this->getZedRequestClient(),
            $this->getConfig(),
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
     * @return \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): SalesOrderAmendmentWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCartReorderClientInterface
     */
    public function getCartReorderClient(): SalesOrderAmendmentWidgetToCartReorderClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentWidgetDependencyProvider::CLIENT_CART_REORDER);
    }

    /**
     * @return \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToSalesOrderAmendmentClientInterface
     */
    public function getSalesOrderAmendmentClient(): SalesOrderAmendmentWidgetToSalesOrderAmendmentClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentWidgetDependencyProvider::CLIENT_SALES_ORDER_AMENDMENT);
    }

    /**
     * @return \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): SalesOrderAmendmentWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToZedRequestClientInterface
     */
    public function getZedRequestClient(): SalesOrderAmendmentWidgetToZedRequestClientInterface
    {
        return $this->getProvidedDependency(SalesOrderAmendmentWidgetDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
