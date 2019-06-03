<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Resolver;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteResolverInterface
{
    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer;
}
