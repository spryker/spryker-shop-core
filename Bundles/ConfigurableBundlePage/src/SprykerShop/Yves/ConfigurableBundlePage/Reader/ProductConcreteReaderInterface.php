<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundlePage\Reader;

interface ProductConcreteReaderInterface
{
    /**
     * @param array<string> $skus
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function getProductConcretesBySkusAndLocale(array $skus, string $localeName): array;
}
