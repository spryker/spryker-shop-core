<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Shared\BusinessOnBehalfWidget\BusinessOnBehalfConstants;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\DisplayOnBehalfBusinessWidget\DisplayOnBehalfBusinessWidgetPluginInterface;

class DisplayOnBehalfBusinessWidgetPlugin extends AbstractWidgetPlugin implements DisplayOnBehalfBusinessWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->addParameter('isOnBehalf', $this->isOnBehalf());
        $this->addParameter('companyName', $this->getCompanyName());
        $this->addParameter('companyBusinessUnitName', $this->getCompanyBusinessUnitName());
        $this->addParameter('isVisible', $this->isVisible());
    }

    /**
     * Specification:
     * - Returns the name of the widget as it's used in templates.
     *
     * @api
     *
     * @return string
     */
    public static function getName()
    {
        return static::NAME;
    }

    /**
     * Specification:
     * - Returns the template file path to render the widget.
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate()
    {
        return '@BusinessOnBehalfWidget/views/shop-layout/company-user-context-text.twig';
    }

    /**
     * @return bool
     */
    protected function isOnBehalf(): bool
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customer) {
            return false;
        }

        return (bool)$customer->getIsOnBehalf();
    }

    /**
     * @return string
     */
    protected function getCompanyName(): string
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customer || !$customer->getCompanyUser()) {
            return '';
        }

        return $customer->getCompanyUser()->requireCompany()->getCompany()->getName();
    }

    /**
     * @return string
     */
    protected function getCompanyBusinessUnitName(): string
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customer || !$customer->getCompanyUser()) {
            return '';
        }

        return $customer->getCompanyUser()->requireCompanyBusinessUnit()->getCompanyBusinessUnit()->getName();
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customer) {
            return false;
        }
        $companyUserCollection = $this->getFactory()->getCompanyUserClient()->findCompanyUserCollectionByCustomerId(
            $customer
        );

        return count($companyUserCollection->getCompanyUsers()) >= BusinessOnBehalfConstants::MIN_COMPANY_USER_ACCOUNT_AMOUNT;
    }
}
