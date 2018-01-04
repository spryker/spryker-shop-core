<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
abstract class AbstractCustomerController extends AbstractController
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

    /**
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return void
     */
    protected function processResponseErrors(CustomerResponseTransfer $customerResponseTransfer)
    {
        foreach ($customerResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessage());
        }
    }
}
