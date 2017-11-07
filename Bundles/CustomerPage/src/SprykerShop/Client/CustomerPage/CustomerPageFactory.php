<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Client\CustomerPage;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerShop\Client\CustomerPage\Zed\CustomerPageStub;

class CustomerPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Client\CustomerPage\Zed\CustomerPageStubInterface
     */
    public function createZedCustomerStub()
    {
        return new CustomerPageStub(
            $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_ZED_REQUEST)
        );
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CART_CLIENT);
    }
}
