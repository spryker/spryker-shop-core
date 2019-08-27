<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetDetailPage\OptionResetUrlGenerator;

use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController;
use Symfony\Component\HttpFoundation\Request;

class OptionResetUrlGenerator implements OptionResetUrlGeneratorInterface
{
    /**
     * @uses \SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController::PARAM_ATTRIBUTE
     */
    public const PARAM_ATTRIBUTE = DetailController::PARAM_ATTRIBUTE;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return string[][]
     */
    public function generateOptionResetUrls(Request $request, array $productViewTransfers): array
    {
        $queryParamsArray = $this->parseRequestToQueryParamsArray($request);
        $optionResetUrls = [];

        foreach ($productViewTransfers as $productViewTransfer) {
            $optionResetUrls += $this->productViewTransferToOptionResetUrls($productViewTransfer, $queryParamsArray);
        }

        return $optionResetUrls;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $queryParamsArray
     *
     * @return string[]
     */
    protected function productViewTransferToOptionResetUrls(
        ProductViewTransfer $productViewTransfer,
        array $queryParamsArray
    ): array {
        $idProduct = $productViewTransfer->getIdProductAbstract();
        $superAttributes = $productViewTransfer
            ->getAttributeMap()
            ->getSuperAttributes();
        $result = [];

        foreach (array_keys($superAttributes) as $optionName) {
            $result[$optionName] = $this->getOptionResetUrl($queryParamsArray, $idProduct, $optionName);
        }

        return [$idProduct => $result];
    }

    /**
     * @param array $queryParamsArray
     * @param int $idProduct
     * @param string $optionName
     *
     * @return string
     */
    protected function getOptionResetUrl(array $queryParamsArray, int $idProduct, string $optionName): string
    {
        unset($queryParamsArray[static::PARAM_ATTRIBUTE][$idProduct][$optionName]);

        return http_build_query($queryParamsArray);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function parseRequestToQueryParamsArray(Request $request): array
    {
        parse_str($request->getQueryString(), $result);

        return $result;
    }
}
