<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\DisplayOnBehalfBusinessWidget\DisplayOnBehalfBusinessWidgetPluginInterface;

class DisplayOnBehalfBusinessWidgetPlugin extends AbstractWidgetPlugin implements DisplayOnBehalfBusinessWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->addParameter('isOnBehalf', $this->getIsOnBehalf());
        $this->addParameter('companyName', $this->getCompanyName());
        $this->addParameter('companyBusinessUnitName', $this->getCompanyBusinessUnitName());
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
     * - Returns the the template file path to render the widget.
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
    protected function getIsOnBehalf(): bool
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

        return $customer->getCompanyUser()->getCompany()->getName();
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

        return $customer->getCompanyUser()->getCompanyBusinessUnit()->getName();
    }
}
