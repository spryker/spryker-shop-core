<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopApplication\Twig;

interface RoutingHelperInterface
{
    /**
     * @param string $destination
     *
     * @return string
     */
    public function getRouteFromDestination($destination);
}
