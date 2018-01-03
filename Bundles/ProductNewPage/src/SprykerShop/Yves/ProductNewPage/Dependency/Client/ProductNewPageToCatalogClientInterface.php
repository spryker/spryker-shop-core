<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Dependency\Client;

use Symfony\Component\HttpFoundation\Request;

interface ProductNewPageToCatalogClientInterface
{
    /**
     * @param Request $request
     *
     * @return string
     */
    public function getCatalogViewMode(Request $request);
}
