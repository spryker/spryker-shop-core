<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\ShopApplication;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Widget\QuoteApprovalStatusWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

/**
 * {@inheritDoc}
 *
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApprovalStatusWidgetCacheKeyGeneratorStrategyPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Generates key for the `QuoteApprovalStatusWidget`.
     *
     * @api
     *
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
        foreach ($arguments as $argument) {
            if ($argument instanceof QuoteTransfer) {
                return serialize($argument->getQuoteApprovals());
            }
        }

        return null;
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
        return QuoteApprovalStatusWidget::class;
    }
}
