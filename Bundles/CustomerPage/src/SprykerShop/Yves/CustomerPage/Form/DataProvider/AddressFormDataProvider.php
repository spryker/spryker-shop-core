<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Form\AddressForm;

class AddressFormDataProvider extends AbstractAddressFormDataProvider
{
    /**
     * @param int|null $idCustomerAddress
     *
     * @return array
     */
    public function getData($idCustomerAddress = null)
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($idCustomerAddress === null) {
            return $this->getDefaultAddressData($customerTransfer);
        }

        $addressTransfer = $this->loadAddressTransfer($customerTransfer, $idCustomerAddress);
        if ($addressTransfer !== null) {
            return $addressTransfer->modifiedToArray();
        }

        return [];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            AddressForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param int|null $idCustomerAddress
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    protected function loadAddressTransfer(CustomerTransfer $customerTransfer, $idCustomerAddress = null)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer
            ->setIdCustomerAddress($idCustomerAddress)
            ->setFkCustomer($customerTransfer->getIdCustomer());

        return $this->customerClient->getAddress($addressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    protected function getDefaultAddressData(CustomerTransfer $customerTransfer)
    {
        return [
            AddressForm::FIELD_FIRST_NAME => $customerTransfer->getFirstName(),
            AddressForm::FIELD_LAST_NAME => $customerTransfer->getLastName(),
        ];
    }
}
