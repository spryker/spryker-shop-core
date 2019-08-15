<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Reader;

use ArrayObject;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToCartClientInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToCartClientInterface $cartClient
     */
    public function __construct(ConfigurableBundleWidgetToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param string $groupKey
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    public function getItemsByConfiguredBundleGroupKey(string $groupKey): ArrayObject
    {
        $itemTransfers = [];
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundle() && $itemTransfer->getConfiguredBundle()->getGroupKey() === $groupKey) {
                $itemTransfers[] = $itemTransfer;
            }
        }

        return new ArrayObject($itemTransfers);
    }
}
