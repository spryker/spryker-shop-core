<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CompanyWidget\CompanyWidgetFactory getFactory()
 */
class CompanyMenuItemWidget extends AbstractWidget
{
    public function __construct()
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $this->addParameter('isVisible', $this->isVisible($customerTransfer))
            ->addParameter('companyName', $this->getCompanyName($customerTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CompanyMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyWidget/views/shop-ui/menu-item-company-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string
     */
    protected function getCompanyName(?CustomerTransfer $customerTransfer): string
    {
        if (
            $customerTransfer !== null
            && $customerTransfer->getCompanyUserTransfer() !== null
            && $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit() !== null
            && $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit()->getCompany() !== null
        ) {
            return $customerTransfer->getCompanyUserTransfer()->getCompanyBusinessUnit()->getCompany()->getName();
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function isVisible(?CustomerTransfer $customerTransfer): bool
    {
        if ($customerTransfer !== null && $customerTransfer->getCompanyUserTransfer() !== null) {
            return true;
        }

        return false;
    }
}
