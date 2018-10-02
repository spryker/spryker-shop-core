<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerNavigationWidget extends AbstractWidget
{
    /**
     * @param string $activePage
     * @param int|null $activeEntityId
     */
    public function __construct(string $activePage, ?int $activeEntityId = null)
    {
        $this->addParameter('activePage', $activePage)
            ->addParameter('activeEntityId', $activeEntityId);

        /** @deprecated Use global widgets instead. */
        $this->addWidgets($this->getFactory()->getCustomerMenuItemWidgetPlugins());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CustomerNavigationWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CustomerPage/views/customer-navigation/customer-navigation.twig';
    }
}
