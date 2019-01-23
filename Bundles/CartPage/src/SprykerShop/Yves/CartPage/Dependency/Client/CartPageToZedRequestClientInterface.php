<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface CartPageToZedRequestClientInterface
{
    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages();

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function getLastResponseTransfer(): ?TransferInterface;
}
