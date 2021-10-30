<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\OptionResetUrlGenerator;

use Symfony\Component\HttpFoundation\Request;

interface OptionResetUrlGeneratorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<array<string, string>>
     */
    public function generateOptionResetUrls(Request $request, array $productViewTransfers): array;
}
