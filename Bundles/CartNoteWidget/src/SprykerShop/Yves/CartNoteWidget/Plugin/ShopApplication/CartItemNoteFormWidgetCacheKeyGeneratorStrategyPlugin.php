<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartNoteWidget\Widget\CartItemNoteFormWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

/**
 * {@inheritDoc}
 *
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class CartItemNoteFormWidgetCacheKeyGeneratorStrategyPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Generates cache key for `CartItemNoteFormWidget`.
     *
     * @api
     *
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
        $keyElements = [];

        foreach ($arguments as $argument) {
            if ($argument instanceof ItemTransfer) {
                $keyElements[] = serialize($argument);

                continue;
            }

            if ($argument instanceof QuoteTransfer) {
                $keyElements[] = sprintf('quote_id:%s', $argument->getIdQuote());
            }
        }

        if (!$keyElements) {
            return null;
        }

        return implode(';', $keyElements);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getWidgetClassName(): string
    {
        return CartItemNoteFormWidget::class;
    }
}
