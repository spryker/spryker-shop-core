<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client\OrderCustomReferenceWidgetToQuoteClientInterface;
use SprykerShop\Yves\OrderCustomReferenceWidget\Setter\OrderCustomReferenceSetter;
use SprykerShop\Yves\OrderCustomReferenceWidget\Setter\OrderCustomReferenceSetterInterface;

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
     * @return \SprykerShop\Yves\OrderCustomReferenceWidget\Setter\OrderCustomReferenceSetterInterface
     */
    public function createOrderCustomReferenceSetter(): OrderCustomReferenceSetterInterface
    {
        return new OrderCustomReferenceSetter(
            $this->getQuoteClient(),
            $this->getOrderCustomReferenceClient()
        );
    }
}
