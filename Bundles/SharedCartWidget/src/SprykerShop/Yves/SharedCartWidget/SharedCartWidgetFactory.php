<?php

namespace SprykerShop\Yves\SharedCartWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class SharedCartWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\SharedCartWidget\Dependency\Client\SharedCartWidgetToCustomerClientInterface
     */
    public function getCustomerClient()
    {
        return $this->getProvidedDependency(SharedCartWidgetDependencyProvider::CLIENT_CUSTOMER);
    }
}
