<?php
/**
 * Created by PhpStorm.
 * User: khatsko
 * Date: 8/7/18
 * Time: 8:06 AM
 */

namespace SprykerShop\Yves\SharedCartWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteTransfer;

interface SharedCartWidgetToSharedCartClientInterface
{
    /**
     * @param QuoteTransfer $quoteTransfer
     * @return string
     */
    public function calculatePermission(QuoteTransfer $quoteTransfer): string;
}
