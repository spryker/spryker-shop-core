<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Plugin\CustomerPage;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CustomerRedirectStrategyPluginInterface;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class BusinessOnBehalfCompanyUserRedirectAfterLoginStrategyPlugin extends AbstractPlugin implements CustomerRedirectStrategyPluginInterface
{
    protected const COMPANY_REDIRECT_ROUTE = 'company/user/select';

    /**
     * {@inheritDoc}
     * - Checks if provided customer has isOnBehalf flag without selected company.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isApplicable(CustomerTransfer $customerTransfer): bool
    {
        return $customerTransfer->getIsOnBehalf()
            && !$customerTransfer->getCompanyUserTransfer()
            && $this->isCompanyUserChangeAllowed($customerTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function getRedirectUrl(CustomerTransfer $customerTransfer): string
    {
        return $this->getFactory()->getApplication()->path(static::COMPANY_REDIRECT_ROUTE);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function isCompanyUserChangeAllowed(CustomerTransfer $customerTransfer): bool
    {
        return $this->getFactory()
            ->getBusinessOnBehalfClient()
            ->isCompanyUserChangeAllowed($customerTransfer);
    }
}
