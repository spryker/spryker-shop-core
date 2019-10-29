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
        if (!$productViewTransfer->getIdProductConcrete()) {
            $this->addParameter('productOfferViewCollection', []);
        }

        $productOfferViewCollection = [];
        $productOfferViewCollectionTransfer = $this->getFactory()->getMerchantProductOfferStorageClient()->findProductOffersByConcreteSku($productViewTransfer->getSku());
        foreach ($productOfferViewCollectionTransfer->getProductOfferViews() as $productOfferView) {
            $merchantProfileViewTransfer = $this->getFactory()->getMerchantProfileStorageClient()->findMerchantProfileStorageViewData($productOfferView->getIdMerchant());
            if ($merchantProfileViewTransfer) {
                $merchantProfileViewTransfer->setMerchantUrl(
                    $this->getResolvedUrl($merchantProfileViewTransfer)
                );
                $productOfferView->setMerchantProfileView($merchantProfileViewTransfer);
                $productOfferViewCollection[] = $productOfferView;
            }
        }

        $this->addParameter('productOfferViewCollection', $productOfferViewCollection);
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
}
