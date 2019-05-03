<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\ResourceShare;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface ResourceShareActivatorInterface
{
    /**
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(string $resourceShareUuid): ResourceShareResponseTransfer;
}
