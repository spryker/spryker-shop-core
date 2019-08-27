<?php

namespace SprykerShop\Yves\ProductSetDetailPage\OptionResetUrlGenerator;

use Symfony\Component\HttpFoundation\Request;

interface OptionResetUrlGeneratorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return string[][]
     */
    public function generateOptionResetUrls(Request $request, array $productViewTransfers): array;
}
