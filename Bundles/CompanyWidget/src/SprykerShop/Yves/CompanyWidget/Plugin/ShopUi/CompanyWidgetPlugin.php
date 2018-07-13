<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopUi\Dependency\Plugin\CompanyWidget\CompanyWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\BusinessOnBehalfWidget\BusinessOnBehalfWidgetFactory getFactory()
 */
class CompanyWidgetPlugin extends AbstractWidgetPlugin implements CompanyWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this
            ->addParameter('isVisible', $this->isVisible())
            ->addParameter('companyName', $this->getCompanyName());
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CompanyWidget/views/shop-ui/company-widget.twig';
    }

    /**
     * @return string
     */
    protected function getCompanyName(): string
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customer
            && $customer->getCompanyUserTransfer()
            && $customer->getCompanyUserTransfer()->getCompanyBusinessUnit()
            && $customer->getCompanyUserTransfer()->getCompanyBusinessUnit()->getCompany()
        ) {
            return $customer->getCompanyUserTransfer()->getCompany()->getName();
        }

        return '';
    }

    /**
     * @return bool
     */
    protected function isVisible(): bool
    {
        $customer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customer && $customer->getCompanyUserTransfer()) {
            return true;
        }

        return false;
    }
}
