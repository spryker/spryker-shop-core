<?php

namespace SprykerShop\Yves\QuoteApprovalWidget\Widget;


use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorInterface;

class QuoteApprovalStatusWidgetCacheKeyGenerator implements WidgetCacheKeyGeneratorInterface
{

    /**
     * @inheritDoc
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
}
