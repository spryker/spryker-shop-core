<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Dependency\Client;

interface CustomerReorderWidgetToZedRequestClientInterface
{
    /**
     * @return void
     */
    public function addFlashMessagesFromLastZedRequest();

    /**
     * @return void
     */
    public function addResponseMessagesToMessenger(): void;
}
