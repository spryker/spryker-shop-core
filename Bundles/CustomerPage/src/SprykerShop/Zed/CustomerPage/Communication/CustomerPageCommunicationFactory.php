<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Zed\CustomerPage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerShop\Zed\CustomerPage\CustomerPageDependencyProvider;

class CustomerPageCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacade
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::SALES_FACADE);
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\NewsletterFacade
     */
    public function getNewsletterFacade()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::NEWSLETTER_FACADE);
    }
}
