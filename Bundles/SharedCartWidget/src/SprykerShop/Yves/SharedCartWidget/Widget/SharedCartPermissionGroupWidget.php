<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Widget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\SharedCartWidget\SharedCartWidgetFactory getFactory()
 */
class SharedCartPermissionGroupWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     */
    public function __construct(QuoteTransfer $quoteTransfer)
    {
        $this->addParameter('cart', $quoteTransfer)
            ->addParameter('accessType', $this->getAccessType($quoteTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function getAccessType(QuoteTransfer $quoteTransfer): ?string
    {
        return $this->getFactory()
            ->getSharedCartClient()
            ->getQuoteAccessLevel($quoteTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SharedCartPermissionGroupWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SharedCartWidget/views/shared-cart-permission/shared-cart-permission.twig';
    }
}
