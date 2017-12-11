<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductReviewWidget\Dependency\Client;

interface ProductReviewWidgetToProductClientInterface
{
    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    public function getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);
}
