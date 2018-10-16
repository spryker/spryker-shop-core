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
    /**
     * @param string $formType
     */
    public function __construct(string $formType)
    {
        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $this->isApplicable())
            ->addParameter('addresses', $this->findAvailableAddressesJson($formType));
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
     * @return string|null
     */
    protected function findAvailableAddressesJson(string $formType): ?string
    {
        $dataProvider = $this->getFactory()
            ->createAddressHandler();

        $customerAddressesArray = $dataProvider->getCustomerAddressesArray($formType);
        $companyBusinessUnitAddressesArray = $dataProvider->getCompanyBusinessUnitAddressesArray($formType);

        return $this->encodeAddressesToJson(
            array_merge(
                $customerAddressesArray,
                $companyBusinessUnitAddressesArray
            )
        );
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
