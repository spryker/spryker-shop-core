<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Dependency\Client;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;

interface ResourceSharePageToResourceShareClientInterface
{
    /**
     * Specification:
     * - Makes Zed-Request.
     * - Returns with "isSuccess=false" and error message if resource is not found by provided uuid.
     * - Applies corresponding strategy using `ResourceShareActivatorStrategyPluginInterface` stack.
     * - Returns with "isLoginRequired=true" when strategy expects logged in customer.
     * - Returns with "isSuccess=true" on success or with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function activateResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer;
}
