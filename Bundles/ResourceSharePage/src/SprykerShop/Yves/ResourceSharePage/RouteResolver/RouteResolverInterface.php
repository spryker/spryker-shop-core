<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\RouteResolver;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Symfony\Component\HttpFoundation\Request;

interface RouteResolverInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param bool $isLoginRequired
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RouteTransfer
     */
    public function resolveRoute(Request $request, bool $isLoginRequired, ResourceShareRequestTransfer $resourceShareRequestTransfer): RouteTransfer;
}
