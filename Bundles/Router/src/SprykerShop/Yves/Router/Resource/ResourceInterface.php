<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router\Resource;

use SprykerShop\Yves\Router\Route\RouteCollection;

interface ResourceInterface
{
    /**
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function __invoke(): RouteCollection;
}
