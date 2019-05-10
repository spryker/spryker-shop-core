<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\Router\Route;

use Symfony\Component\Routing\Route as SymfonyRoute;

class Route extends SymfonyRoute
{
    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethods($method)
    {
        if (is_string($method)) {
            parent::setMethods(explode('|', $method));

            return $this;
        }

        parent::setMethods($method);

        return $this;
    }
}
