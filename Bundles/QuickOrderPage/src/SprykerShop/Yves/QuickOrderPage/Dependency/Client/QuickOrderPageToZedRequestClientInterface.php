<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

interface QuickOrderPageToZedRequestClientInterface
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
     * @return void
     */
    public function addAllResponseMessagesToMessenger(): void;

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getAllResponsesErrorMessages(): array;
}
