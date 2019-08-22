<?php

namespace SprykerShop\Yves\ProductSetDetailPage\OptionsResetUrlGenerator;

use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\ProductSetDetailPage\Controller\DetailController;
use Symfony\Component\HttpFoundation\Request;

class OptionsResetUrlGenerator implements OptionsResetUrlGeneratorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return array
     */
    public function generateOptionResetUrls(Request $request, array $productViewTransfers): array
    {
        $queryParamsArray = $this->requestToQueryParamsArray($request);
        $result = [];

        foreach ($productViewTransfers as $productViewTransfer) {
            $result += $this->productViewTransferToOptionResetUrls($productViewTransfer, $queryParamsArray);
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $queryParamsArray
     *
     * @return array
     */
    protected function productViewTransferToOptionResetUrls(
        ProductViewTransfer $productViewTransfer,
        array $queryParamsArray
    ): array
    {
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
        unset($queryParamsArray[DetailController::PARAM_ATTRIBUTE][$idProduct][$optionName]);

        return http_build_query($queryParamsArray);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function requestToQueryParamsArray(Request $request): array
    {
        parse_str($request->getQueryString(), $result);

        return $result;
    }
}
