<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Dependency\Client;

interface CartPageToZedRequestClientInterface
{
    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseErrorMessages();

    /**
     * @return void
     */
    public function addResponseMessagesToMessenger(): void;

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getResponsesErrorMessages(): array;

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseSuccessMessages();

    /**
     * @return array<\Generated\Shared\Transfer\MessageTransfer>
     */
    public function getLastResponseInfoMessages(): array;
}
