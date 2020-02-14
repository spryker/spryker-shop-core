<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client;

use Generated\Shared\Transfer\MerchantSwitchRequestTransfer;
use Generated\Shared\Transfer\MerchantSwitchResponseTransfer;

class MerchantSwitcherWidgetToMerchantSwitcherClientBridge implements MerchantSwitcherWidgetToMerchantSwitcherClientInterface
{
    /**
     * @var \Spryker\Client\MerchantSwitcher\MerchantSwitcherClientInterface
     */
    protected $merchantSwitcherClient;

    /**
     * @param \Spryker\Client\MerchantSwitcher\MerchantSwitcherClientInterface $merchantSwitcherClient
     */
    public function __construct($merchantSwitcherClient)
    {
        $this->merchantSwitcherClient = $merchantSwitcherClient;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSwitchResponseTransfer
     */
    public function switchMerchant(MerchantSwitchRequestTransfer $merchantSwitchRequestTransfer): MerchantSwitchResponseTransfer
    {
        return $this->merchantSwitcherClient->switchMerchant($merchantSwitchRequestTransfer);
    }
}
