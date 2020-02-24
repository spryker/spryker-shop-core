<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Form\OrderCustomReferenceForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class OrderCustomReferenceWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface
     */
    public function getOrderCustomReferenceClient(): OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceWidgetDependencyProvider::CLIENT_ORDER_CUSTOM_REFERENCE);
    }

    /**
     * @return \SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface
     */
    public function getQuoteClient(): OrderCustomReferenceWidgetToQuoteClientInterface
    {
        return $this->getProvidedDependency(OrderCustomReferenceWidgetDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @param array $data
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderCustomReferenceForm(array $data = []): FormInterface
    {
        return $this->getFormFactory()->create(OrderCustomReferenceForm::class, $data);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }
}
