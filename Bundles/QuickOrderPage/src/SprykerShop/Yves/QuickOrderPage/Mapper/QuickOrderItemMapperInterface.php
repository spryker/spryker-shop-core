<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Mapper;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Symfony\Component\HttpFoundation\Request;

interface QuickOrderItemMapperInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function mapRequestToQuickOrderItemTransfer(
        Request $request,
        QuickOrderItemTransfer $quickOrderItemTransfer
    ): QuickOrderItemTransfer;
}
