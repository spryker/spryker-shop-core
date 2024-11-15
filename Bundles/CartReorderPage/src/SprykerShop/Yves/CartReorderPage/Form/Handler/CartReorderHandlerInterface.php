<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartReorderPage\Form\Handler;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Symfony\Component\HttpFoundation\Request;

interface CartReorderHandlerInterface
{
    /**
     * @param string $orderReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(string $orderReference, Request $request): CartReorderResponseTransfer;
}
