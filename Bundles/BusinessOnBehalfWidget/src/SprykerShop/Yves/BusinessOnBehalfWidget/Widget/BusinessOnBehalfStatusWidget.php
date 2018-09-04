<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Widget;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\BusinessOnBehalfWidget\BusinessOnBehalfWidgetFactory getFactory()
 */
class BusinessOnBehalfStatusWidget extends AbstractWidget
{
    public function __construct()
    {
        $customer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $this->addParameter('isOnBehalf', $this->isOnBehalf($customer))
            ->addParameter('companyName', $this->getCompanyName($customer))
            ->addParameter('companyBusinessUnitName', $this->getCompanyBusinessUnitName($customer))
            ->addParameter('isVisible', $this->isVisible($customer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'BusinessOnBehalfStatusWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        // TODO: This widget is providing 2 templates to use, but it's not obvious which templates it's compatible with. We need to clarify this.
        return '@BusinessOnBehalfWidget/views/business-on-behalf-status/business-on-behalf-status.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function isOnBehalf(?CustomerTransfer $customerTransfer): bool
    {
        if (!$customerTransfer || !$customerTransfer->getIsOnBehalf()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string
     */
    protected function getCompanyName(?CustomerTransfer $customerTransfer): string
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$customerTransfer || !$companyUserTransfer || !$companyUserTransfer->getCompany()) {
            return '';
        }

        return $companyUserTransfer->getCompany()->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return string
     */
    protected function getCompanyBusinessUnitName(?CustomerTransfer $customerTransfer): string
    {
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if (!$customerTransfer || !$companyUserTransfer || !$companyUserTransfer->getCompanyBusinessUnit()) {
            return '';
        }

        return $companyUserTransfer->getCompanyBusinessUnit()->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    protected function isVisible(?CustomerTransfer $customerTransfer): bool
    {
        if (!$customerTransfer || !$customerTransfer->getIsOnBehalf()) {
            return false;
        }

        return true;
    }
}
