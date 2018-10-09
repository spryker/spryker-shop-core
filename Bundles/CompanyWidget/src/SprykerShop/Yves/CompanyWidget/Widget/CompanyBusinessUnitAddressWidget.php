<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CompanyWidget\Widget;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\AbstractType;

/**
 * @method \SprykerShop\Yves\CompanyWidget\CompanyWidgetFactory getFactory()
 */
class CompanyBusinessUnitAddressWidget extends AbstractWidget
{
    protected const NAME = 'CompanyBusinessUnitAddressWidget';

    /**
     * @param \Symfony\Component\Form\AbstractType $formType
     */
    public function __construct(AbstractType $formType)
    {
        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->getIsApplicable())
            ->addParameter('customerAddresses', $this->getCustomerAddresses())
            ->addParameter('businessUnitAddresses', $this->findCompanyBusinessUnitAddresses());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyWidget/views/address/address.twig';
    }

    /**
     * @return bool
     */
    public function getIsApplicable(): bool
    {
        if ($this->getCustomer()->getCompanyUserTransfer() === null) {
            return false;
        }

        if ($this->findCompanyBusinessUnitAddresses()->count() === 0) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getCustomerAddresses(): array
    {
        return $this->getCustomer()
            ->getAddresses()
            ->toArray();
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUnitAddressTransfer[]
     */
    protected function findCompanyBusinessUnitAddresses(): ArrayObject
    {
        $companyBusinessUnit = $this->findCompanyBusinessUnit();
        if ($companyBusinessUnit !== null) {
            return $companyBusinessUnit->getAddressCollection()->getCompanyUnitAddresses();
        }

        return new ArrayObject();
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    protected function findCompanyBusinessUnit(): ?CompanyBusinessUnitTransfer
    {
        $customerTransfer = $this->getCustomer();

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();
        if ($companyUserTransfer !== null) {
            return $companyUserTransfer->getCompanyBusinessUnit();
        }

        return null;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer(): CustomerTransfer
    {
        return $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();
    }
}
