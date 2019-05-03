<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\RouteResolver;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\RouteTransfer;

interface RouteResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceShareResponseTransfer $resourceShareResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RouteTransfer|null
     */
    public function resolveRoute(ResourceShareResponseTransfer $resourceShareResponseTransfer): ?RouteTransfer;
}
