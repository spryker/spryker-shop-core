<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartWidget\Plugin\MultiCartWidget;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\MultiCartWidget\Dependency\Plugin\SharedCartWidget\SharedCartMultiCartAddWidgetPluginInterface;

class SharedCartMultiCartAddWidgetPlugin extends AbstractWidgetPlugin implements SharedCartMultiCartAddWidgetPluginInterface
{
    use PermissionAwareTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $activeQuoteTransfer
     * @param array $quoteCollection
     * @param bool $isButtonDisabled
     *
     * @return void
     */
    public function initialize(QuoteTransfer $activeQuoteTransfer, array $quoteCollection, bool $isButtonDisabled): void
    {
        $writeAllowed = $this->can(WriteSharedCartPermissionPlugin::KEY, $activeQuoteTransfer->getIdQuote());
        $this
            ->addParameter('cart', $activeQuoteTransfer)
            ->addParameter('carts', $quoteCollection)
            ->addParameter('disabled', $isButtonDisabled)
            ->addParameter('disabledButton', $isButtonDisabled || !$writeAllowed);
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
        return '@SharedCartWidget/views/multi-cart-widget/shared-cart-multi-cart-add.twig';
    }
}
