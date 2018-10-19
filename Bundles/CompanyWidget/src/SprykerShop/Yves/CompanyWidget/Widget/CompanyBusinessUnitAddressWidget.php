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
        $addressProvider = $this->getFactory()
            ->createAddressProvider();

        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $addressProvider->companyBusinessUnitAddressesExists())
            ->addParameter('addresses', $addressProvider->getCombinedAddressesListJson($formType))
            ->addParameter('fullAddresses', $addressProvider->getCombinedFullAddressesList($formType));
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
    protected function isApplicable(): bool
    {
        return $this->getFactory()
            ->createAddressProvider()
            ->companyBusinessUnitAddressesExists();
    }
}
