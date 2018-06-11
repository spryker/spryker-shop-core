<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\BusinessOnBehalfWidget\DisplayOnBehalfBusinessWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\BusinessOnBehalfWidget\BusinessOnBehalfWidgetFactory getFactory()
 */
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
    public static function getName(): string
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
    public static function getTemplate(): string
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

        if (!$customer
            || !$customer->getCompanyUserTransfer()
            || !$customer->getCompanyUserTransfer()->getCompany()
        ) {
            return '';
        }

        return $customer->getCompanyUserTransfer()->getCompany()->getName();
    }

    /**
     * @return string
     */
    protected function getCompanyBusinessUnitName(): string
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customer
            || !$customer->getCompanyUserTransfer()
            || !$customer->getCompanyUserTransfer()->getCompanyBusinessUnit()
        ) {
            return '';
        }

        return $customer->getCompanyUserTransfer()->getCompanyBusinessUnit()->getName();
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

        return $customer->getIsOnBehalf();
    }
}
