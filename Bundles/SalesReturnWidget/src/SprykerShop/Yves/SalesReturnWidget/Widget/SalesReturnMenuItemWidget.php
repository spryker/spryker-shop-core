<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class SalesReturnMenuItemWidget extends AbstractWidget
{
    protected const PARAMETER_IS_ACTIVE_PAGE = 'isActivePage';
    protected const PAGE_KEY_SALES_RETURN = 'sales-return';

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addParameter(static::PARAMETER_IS_ACTIVE_PAGE, $activePage === static::PAGE_KEY_SALES_RETURN);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SalesReturnMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SalesReturnWidget/views/sales-return-menu-item/sales-return-menu-item.twig';
    }
}
