<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Widget;

use Generated\Shared\Transfer\MerchantProfileViewTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOfferWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter('productOfferViewCollection', $this->getProductOfferViewCollection($productViewTransfer));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantProductOfferWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductOfferWidget/views/merchant-product-offer-widget/merchant-product-offer-widget.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileViewTransfer $merchantProfileViewTransfer
     *
     * @return string
     */
    protected function getResolvedUrl(MerchantProfileViewTransfer $merchantProfileViewTransfer): string
    {
        $locale = strstr($this->getLocale(), '_', true);

        foreach ($merchantProfileViewTransfer->getUrlCollection() as $merchantProfileViewUrlTransfer) {
            $urlLocale = mb_substr($merchantProfileViewUrlTransfer->getUrl(), 1, 2);
            if ($locale === $urlLocale) {
                return $merchantProfileViewUrlTransfer->getUrl();
            }
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferViewTransfer[]
     */
    protected function getProductOfferViewCollection(ProductViewTransfer $productViewTransfer): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }
        $productOfferViewCollection = [];
        $productOfferViewCollectionTransfer = $this->getFactory()->getMerchantProductOfferStorageClient()->getProductOfferViewCollection($productViewTransfer->getSku());
        foreach ($productOfferViewCollectionTransfer->getProductOfferViews() as $productOfferViewTransfer) {
            $merchantProfileViewTransfer = $this->getFactory()->getMerchantProfileStorageClient()->findMerchantProfileStorageViewData($productOfferViewTransfer->getIdMerchant());
            if ($merchantProfileViewTransfer) {
                $merchantProfileViewTransfer->setMerchantUrl(
                    $this->getResolvedUrl($merchantProfileViewTransfer)
                );
                $productOfferViewTransfer->setMerchantProfileView($merchantProfileViewTransfer);
                $productOfferViewCollection[] = $productOfferViewTransfer;
            }
        }

        return $productOfferViewCollection;
    }
}
