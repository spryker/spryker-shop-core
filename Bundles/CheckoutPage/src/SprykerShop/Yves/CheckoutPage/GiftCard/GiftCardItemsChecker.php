<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\GiftCard;

use ArrayObject;

class GiftCardItemsChecker implements GiftCardItemsCheckerInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    public function hasOnlyGiftCardItems(ArrayObject $itemTransfers): bool
    {
        foreach ($itemTransfers as $itemTransfer) {
            $giftCardMetadata = $itemTransfer->getGiftCardMetadata();
            if($giftCardMetadata === null) {
                return false;
            }

            $isGiftCard = $giftCardMetadata->getIsGiftCard();
            if($isGiftCard === false || $isGiftCard === null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return bool
     */
    public function hasGiftCardItems(ArrayObject $itemTransfers): bool
    {
        foreach ($itemTransfers as $itemTransfer) {
            $giftCardMetadata = $itemTransfer->getGiftCardMetadata();
            if($giftCardMetadata === null) {
                continue;
            }

            $isGiftCard = $giftCardMetadata->getIsGiftCard();
            if($isGiftCard === true) {
                return true;
            }
        }

        return false;
    }
}
