<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Expander\OrderSearchFormFormExpander;
use SprykerShop\Yves\CompanyBusinessUnitWidget\Expander\OrderSearchFormFormExpanderInterface;

class CompanyBusinessUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CompanyBusinessUnitWidget\Expander\OrderSearchFormFormExpanderInterface
     */
    public function createOrderSearchFormFormExpander(): OrderSearchFormFormExpanderInterface
    {
        return new OrderSearchFormFormExpander();
    }
}
