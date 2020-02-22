<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Dependency\Client;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface OrderCustomReferenceWidgetToOrderCustomReferenceClientInterface
{
    /**
     * @param string|null $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(?string $orderCustomReference): QuoteResponseTransfer;
}
