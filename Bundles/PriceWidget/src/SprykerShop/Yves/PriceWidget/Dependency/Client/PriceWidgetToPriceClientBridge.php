<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceWidget\Dependency\Client;

class PriceWidgetToPriceClientBridge implements PriceWidgetToPriceClientInterface
{
    /**
     * @var \Spryker\Client\Price\PriceClientInterface
     */
    protected $priceClient;

    /**
     * @param \Spryker\Client\Price\PriceClientInterface $priceClient
     */
    public function __construct($priceClient)
    {
        $this->priceClient = $priceClient;
    }

    /**
     * @return string[]
     */
    public function getPriceModes()
    {
        return $this->priceClient->getPriceModes();
    }

    /**
     * @return string
     */
    public function getCurrentPriceMode()
    {
        return $this->priceClient->getCurrentPriceMode();
    }
}
