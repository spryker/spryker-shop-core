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
        $addressHandler = $this->getFactory()
            ->createAddressHandler();

        $this->addParameter('formType', $formType)
            ->addParameter('isApplicable', $addressHandler->isApplicable())
            ->addParameter('addresses', $addressHandler->getCombinedAddressesListJson($formType))
            ->addParameter('fullAddresses', $addressHandler->getCombinedFullAddressesList($formType));
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
}
