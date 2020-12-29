<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\ShopApplication;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\Widget\QuoteApproveRequestWidget;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorStrategyPluginInterface;

/**
 * {@inheritDoc}
 *
 * @method \SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetFactory getFactory()
 */
class QuoteApproveRequestWidgetCacheKeyGeneratorStrategyPlugin extends AbstractPlugin implements WidgetCacheKeyGeneratorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Disables cache for `QuoteApproveRequestWidget`.
     *
     * @api
     *
     * @param array $arguments
     *
     * @return string|null
     */
    public function generateCacheKey(array $arguments = []): ?string
    {
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
        return QuoteApproveRequestWidget::class;
    }
}
