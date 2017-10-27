<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget\Plugin\CartPage;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;

use SprykerShop\Yves\CartPage\Dependency\Plugin\DiscountWidget\DiscountSummaryWidgetPluginInterface;
use SprykerShop\Yves\DiscountWidget\DiscountWidgetFactory;

/**
 * Class DiscountVoucherFormWidgetPlugin
 *
 * @method DiscountWidgetFactory getFactory()
 */
class DiscountSummaryWidgetPlugin extends AbstractWidgetPlugin implements DiscountSummaryWidgetPluginInterface
{

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this->addParameter('cart', $quoteTransfer);
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@DiscountWidget/_cart-page/discount-summary.twig';
    }
}
