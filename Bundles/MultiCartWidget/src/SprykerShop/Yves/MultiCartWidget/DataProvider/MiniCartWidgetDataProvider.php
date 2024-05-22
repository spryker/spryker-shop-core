<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget\DataProvider;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientInterface;

class MiniCartWidgetDataProvider implements MiniCartWidgetDataProviderInterface
{
    use PermissionAwareTrait;

    /**
     * @uses \Spryker\Client\SharedCart\Plugin\ReadSharedCartPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_READ_SHARED_CART = 'ReadSharedCartPermissionPlugin';

    /**
     * @var \SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientInterface
     */
    protected MultiCartWidgetToMultiCartClientInterface $multiCartClient;

    /**
     * @param \SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientInterface $multiCartClient
     */
    public function __construct(MultiCartWidgetToMultiCartClientInterface $multiCartClient)
    {
        $this->multiCartClient = $multiCartClient;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getActiveCart(): QuoteTransfer
    {
        return $this->multiCartClient->getDefaultCart();
    }

    /**
     * @return array<\Generated\Shared\Transfer\QuoteTransfer>
     */
    public function getInActiveQuoteList(): array
    {
        $quoteCollectionTransfer = $this->multiCartClient->getQuoteCollection();

        $inActiveQuoteTransferList = [];
        foreach ($quoteCollectionTransfer->getQuotes() as $quoteTransfer) {
            if (!$quoteTransfer->getIsDefault() && $this->can(static::PERMISSION_KEY_READ_SHARED_CART, $quoteTransfer->getIdQuote())) {
                $inActiveQuoteTransferList[] = $quoteTransfer;
            }
        }

        return $inActiveQuoteTransferList;
    }

    /**
     * @return bool
     */
    public function isMultiCartAllowed(): bool
    {
        return $this->multiCartClient->isMultiCartAllowed();
    }
}
