<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Dependency\Client;

interface CartCodeWidgetToCartCodeClientInterface
{
    public function addCode(string $code);

    public function removeCode(string $code);

    public function clearCodes();
}
