<?php


namespace SprykerShop\Yves\ProductSetDetailPage\OptionsResetUrlGenerator;

use Symfony\Component\HttpFoundation\Request;

interface OptionsResetUrlGeneratorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return array
     */
    public function generateOptionResetUrls(Request $request, array $productViewTransfers): array;
}
