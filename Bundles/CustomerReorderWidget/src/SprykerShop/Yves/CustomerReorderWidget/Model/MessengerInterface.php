<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Model;

interface MessengerInterface
{
    /**
     * @return void
     */
    public function setFlashMessagesFromLastZedRequest(): void;
}
