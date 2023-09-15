<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerShopTest\Yves\ProductOfferServicePointAvailabilityWidget;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductOfferServicePointAvailabilityResponseItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerShopTest\Yves\ProductOfferServicePointAvailabilityWidget\PHPMD)
 */
class ProductOfferServicePointAvailabilityWidgetYvesTester extends Actor
{
    use _generated\ProductOfferServicePointAvailabilityWidgetYvesTesterActions;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(array $seed = []): QuoteTransfer
    {
        return (new QuoteBuilder($seed))->build();
    }

    /**
     * @param bool $isAvailable
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer
     */
    public function createProductOfferServicePointAvailabilityResponseItemTransfer(
        bool $isAvailable
    ): ProductOfferServicePointAvailabilityResponseItemTransfer {
        return (new ProductOfferServicePointAvailabilityResponseItemBuilder([
            ProductOfferServicePointAvailabilityResponseItemTransfer::IS_AVAILABLE => $isAvailable,
        ]))->build();
    }
}
