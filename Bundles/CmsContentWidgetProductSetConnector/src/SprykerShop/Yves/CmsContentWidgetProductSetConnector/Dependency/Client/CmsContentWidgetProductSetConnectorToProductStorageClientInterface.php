<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Client;

use Generated\Shared\Transfer\ProductViewTransfer;
use Symfony\Component\HttpFoundation\Request;

interface CmsContentWidgetProductSetConnectorToProductStorageClientInterface
{
    /**
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer;

    /**
     * @param array<int> $productAbstractIds
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function getProductAbstractViewTransfers(array $productAbstractIds, string $localeName, array $selectedAttributes = []): array;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param list<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<int, array<string, string>>
     */
    public function generateProductAttributesResetUrlQueryParameters(Request $request, array $productViewTransfers): array;
}
