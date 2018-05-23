<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Dependency\Client;

interface PriceWidgetToPriceClientInterface
{
    /**
     * @return string[]
     */
    public function getPriceModes();

    /**
     * @return string
     */
    public function getCurrentPriceMode();

    /**
     * @param string $priceMode
     *
     * @return void
     */
    public function switchPriceMode(string $priceMode): void;
}
