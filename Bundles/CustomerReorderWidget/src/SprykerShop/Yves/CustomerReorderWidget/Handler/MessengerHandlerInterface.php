<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

interface MessengerHandlerInterface
{
    /**
     * In all meanings dirty jack copied from CartPage
     *
     * @TODO discuss with @Nyulas
     *
     * @return void
     */
    public function setFlashMessagesFromLastZedRequest(): void;
}
