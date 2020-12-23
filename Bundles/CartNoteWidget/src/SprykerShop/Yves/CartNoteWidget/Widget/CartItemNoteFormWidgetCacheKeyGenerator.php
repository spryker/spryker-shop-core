<?php

namespace SprykerShop\Yves\CartNoteWidget\Widget;


use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\WidgetCacheKeyGeneratorInterface;

class CartItemNoteFormWidgetCacheKeyGenerator implements WidgetCacheKeyGeneratorInterface
{

    /**
     * @inheritDoc
     */
    public function generateCacheKey(array $arguments = []): string
    {
        $key = '';

        foreach ($arguments as $argument) {
            if ($argument instanceof ItemTransfer) {
                $key .= serialize($argument);
                continue;
            }

            if ($argument instanceof QuoteTransfer) {
                $key .= sprintf('quote_id:%s', $argument->getIdQuote());
            }
        }

        return $key;
    }
}
