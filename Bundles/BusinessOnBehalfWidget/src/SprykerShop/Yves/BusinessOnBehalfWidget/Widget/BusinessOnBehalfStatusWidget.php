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
    protected const PARAMETER_IS_COMPANY_USER_CHANGE_ALLOWED = 'isCompanyUserChangeAllowed';
    protected const PARAMETER_IS_ON_BEHALF = 'isOnBehalf';
    protected const PARAMETER_COMPANY_NAME = 'companyName';
    protected const PARAMETER_COMPANY_BUSINESS_UNIT_NAME = 'companyBusinessUnitName';
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    public function __construct()
    {
        $customerTransfer = $this->getCustomerTransfer();

        $this->addIsOnBehalfParameter($customerTransfer)
            ->addCompanyNameParameter($customerTransfer)
            ->addCompanyBusinessUnitNameParameter($customerTransfer)
            ->addIsVisibleParameter($customerTransfer)
            ->addIsCompanyUserChangeAllowedParameter($customerTransfer);
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
        return '@BusinessOnBehalfWidget/views/business-on-behalf-status/business-on-behalf-status.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomerTransfer(): CustomerTransfer
    {
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer === null) {
            return new CustomerTransfer();
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return $this
     */
    protected function addIsCompanyUserChangeAllowedParameter(CustomerTransfer $customerTransfer)
    {
        $businessOnBehalfClient = $this->getFactory()
            ->getBusinessOnBehalfClient();

        $isAllowed = $customerTransfer->getIsOnBehalf()
            && $businessOnBehalfClient->isCompanyUserChangeAllowed($customerTransfer);
        $this->addParameter(static::PARAMETER_IS_COMPANY_USER_CHANGE_ALLOWED, $isAllowed);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return $this
     */
    protected function addIsOnBehalfParameter(CustomerTransfer $customerTransfer)
    {
        $this->addParameter(static::PARAMETER_IS_ON_BEHALF, (bool)$customerTransfer->getIsOnBehalf());

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return $this
     */
    protected function addCompanyNameParameter(CustomerTransfer $customerTransfer)
    {
        $companyName = '';
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer && $companyUserTransfer->getCompany()) {
            $companyName = $companyUserTransfer->getCompany()->getName();
        }

        $this->addParameter(static::PARAMETER_COMPANY_NAME, $companyName);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitNameParameter(CustomerTransfer $customerTransfer)
    {
        $companyBusinessUnitName = '';
        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer && $companyUserTransfer->getCompanyBusinessUnit()) {
            $companyBusinessUnitName = $companyUserTransfer->getCompanyBusinessUnit()->getName();
        }

        $this->addParameter(static::PARAMETER_COMPANY_NAME, $companyBusinessUnitName);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return $this
     */
    protected function addIsVisibleParameter(CustomerTransfer $customerTransfer)
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, (bool)$customerTransfer->getIsOnBehalf());

        return $this;
    }
}
