<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
abstract class AbstractCompanyController extends AbstractController
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getLoggedInCustomerTransfer()
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            return $this->getFactory()->getCustomerClient()->getCustomer();
        }

        return null;
    }

    /**
     * @return bool
     */
    protected function isLoggedInCustomer()
    {
        return $this->getFactory()->getCustomerClient()->isLoggedIn();
    }
}
