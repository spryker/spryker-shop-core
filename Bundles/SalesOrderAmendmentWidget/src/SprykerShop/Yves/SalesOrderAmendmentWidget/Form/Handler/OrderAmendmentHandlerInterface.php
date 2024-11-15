<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Form\Handler;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Symfony\Component\HttpFoundation\Request;

interface OrderAmendmentHandlerInterface
{
    /**
     * @param string $orderReference
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function amendOrder(string $orderReference, Request $request): CartReorderResponseTransfer;
}
