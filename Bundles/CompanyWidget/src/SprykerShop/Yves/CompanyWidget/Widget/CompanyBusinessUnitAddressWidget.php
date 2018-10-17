<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CompanyWidget\CompanyWidgetFactory getFactory()
 */
class CompanyBusinessUnitAddressWidget extends AbstractWidget
{
    protected const KEY_ADDRESS_DEFAULT = 'addressesForm[%s][default]';

    /**
     * @param string $formType
     */
    public function __construct(string $formType)
    {
        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->isApplicable())
            ->addParameter('addresses', $this->findAvailableAddressesJson($formType))
            ->addParameter('fullAddresses', $this->getAvailableFullAddresses($formType));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CompanyBusinessUnitAddressWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyWidget/views/company-business-unit-address/company-business-unit-address.twig';
    }

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        $dataProvider = $this->getFactory()
            ->createAddressHandler();

        return ($dataProvider->getCompanyBusinessUnitAddresses()->count() > 0);
    }

    /**
     * @param string $formType
     *
     * @return array
     */
    protected function getAvailableFullAddresses(string $formType): array
    {
        return $this->getFactory()
            ->createAddressHandler()
            ->getAvailableFullAddresses($formType);
    }

    /**
     * @param string $formType
     *
     * @return string|null
     */
    protected function findAvailableAddressesJson(string $formType): ?string
    {
        $addressHandler = $this->getFactory()
            ->createAddressHandler();

        $customerAddressesArray = $addressHandler->getCustomerAddressesArray($formType);
        $companyBusinessUnitAddressesArray = $addressHandler->getCompanyBusinessUnitAddressesArray($formType);

        $defaultCustomerAddressIndexes = $this->getDefaultAddressIndexes($customerAddressesArray, $formType);
        $defaultCompanyBusinessUnitAddressIndexes = $this->getDefaultAddressIndexes($companyBusinessUnitAddressesArray, $formType);

        if ((count($defaultCustomerAddressIndexes) > 0 && count($defaultCompanyBusinessUnitAddressIndexes) > 0)
            || (count($defaultCustomerAddressIndexes) === 0 && count($defaultCompanyBusinessUnitAddressIndexes) === 0)
        ) {
            $companyBusinessUnitAddressesArray = $this->resetAddressesDefaultValues(
                $companyBusinessUnitAddressesArray,
                $defaultCompanyBusinessUnitAddressIndexes,
                $formType
            );
        }

        return $this->encodeAddressesToJson(
            array_merge(
                $customerAddressesArray,
                $companyBusinessUnitAddressesArray
            )
        );
    }

    /**
     * @param array $addressesArray
     * @param int[] $addressIndexes
     * @param string $formType
     *
     * @return array
     */
    protected function resetAddressesDefaultValues(array $addressesArray, array $addressIndexes, string $formType): array
    {
        foreach ($addressIndexes as $addressIndex) {
            $addressesArray[$addressIndex][sprintf(static::KEY_ADDRESS_DEFAULT, $formType)] = false;
        }

        return $addressesArray;
    }

    /**
     * @param array $addressesArray
     * @param string $formType
     *
     * @return int[]
     */
    protected function getDefaultAddressIndexes(array $addressesArray, string $formType): array
    {
        $index = 0;
        $defaultAddressIndexes = [];
        foreach ($addressesArray as $addressItem) {
            if ($addressItem[sprintf(static::KEY_ADDRESS_DEFAULT, $formType)] === true) {
                $defaultAddressIndexes[] = $index;
            }

            $index++;
        }

        return $defaultAddressIndexes;
    }

    /**
     * @param array $addresses
     *
     * @return string|null
     */
    protected function encodeAddressesToJson(array $addresses): ?string
    {
        $jsonEncodedAddresses = json_encode($addresses, JSON_PRETTY_PRINT);

        return ($jsonEncodedAddresses !== false)
            ? $jsonEncodedAddresses
            : null;
    }
}
