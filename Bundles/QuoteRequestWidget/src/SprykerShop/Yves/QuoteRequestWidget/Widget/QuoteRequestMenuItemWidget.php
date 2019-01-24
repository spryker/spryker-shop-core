<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\QuoteRequestWidget\QuoteRequestWidgetFactory getFactory()
 */
class QuoteRequestMenuItemWidget extends AbstractWidget
{
    protected const PAGE_KEY_QUOTE_REQUEST = 'quoteRequest';

    /**
     * @param string $activePage
     */
    public function __construct(string $activePage)
    {
        $this->addParameter('isActivePage', $this->isQuoteRequestPageActive($activePage));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'QuoteRequestMenuItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@QuoteRequestWidget/views/quote-request-menu-item/quote-request-menu-item.twig';
    }

    /**
     * @param string $activePage
     *
     * @return bool
     */
    protected function isQuoteRequestPageActive(string $activePage): bool
    {
        return $activePage === static::PAGE_KEY_QUOTE_REQUEST;
    }
}
