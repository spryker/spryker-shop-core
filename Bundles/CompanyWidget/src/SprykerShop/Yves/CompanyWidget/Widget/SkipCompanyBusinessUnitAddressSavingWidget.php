<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use SprykerShop\Yves\CompanyWidget\Address\AddressProviderInterface;

/**
 * @method \SprykerShop\Yves\CompanyWidget\CompanyWidgetFactory getFactory()
 */
class SkipCompanyBusinessUnitAddressSavingWidget extends AbstractWidget
{
    /**
     * @param array $templateAttributes
     */
    public function __construct(array $templateAttributes)
    {
        $addressProvider = $this->getFactory()
            ->createAddressProvider();

        $this->addIsApplicableParameter($addressProvider);
        $this->addTemplateAttributesParameter($templateAttributes);
    }

    /**
     * @param array $templateAttributes
     *
     * @return void
     */
    protected function addTemplateAttributesParameter(array $templateAttributes): void
    {
        $this->addParameter('templateAttributes', $templateAttributes);
    }

    /**
     * @param \SprykerShop\Yves\CompanyWidget\Address\AddressProviderInterface $addressProvider
     *
     * @return void
     */
    protected function addIsApplicableParameter(AddressProviderInterface $addressProvider): void
    {
        $this->addParameter('isApplicable', $addressProvider->companyBusinessUnitAddressesExists());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SkipCompanyBusinessUnitAddressSavingWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyWidget/views/skip-company-business-unit-address-saving/skip-company-business-unit-address-saving.twig';
    }
}
