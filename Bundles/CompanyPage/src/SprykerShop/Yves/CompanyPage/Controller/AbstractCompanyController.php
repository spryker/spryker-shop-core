<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
abstract class AbstractCompanyController extends AbstractController
{
    /**
     * @return bool
     */
    protected function isLoggedInCustomer(): bool
    {
        return $this->getFactory()->getCustomerClient()->isLoggedIn();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function getCompanyUser(): ?CompanyUserTransfer
    {
        return $this->getFactory()->getCompanyUserClient()->getCompanyUser();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $responseTransfer
     *
     * @return void
     */
    protected function processResponseErrors(AbstractTransfer $responseTransfer): void
    {
        if ($responseTransfer->offsetExists('errors')) {
            $responseErrors = $responseTransfer->offsetGet('errors');
            /** @var \Generated\Shared\Transfer\ResponseErrorTransfer $errorTransfer */
            foreach ($responseErrors as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessage());
            }
        }
    }
}
