<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOffersSelectWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_DATA_URL = 'dataUrl';

    /**
     * @uses \SprykerShop\Yves\MerchantProductOfferWidget\Plugin\Router\MerchantProductOfferWidgetRouteProviderPlugin::ROUTE_NAME_MERCHANT_PRODUCT_OFFERS_SELECT
     *
     * @var string
     */
    protected const ROUTE_NAME_MERCHANT_PRODUCT_OFFERS_SELECT = 'merchant-product-offer-widget/merchant-product-offers-select';

    public function __construct()
    {
        $this->addDataUrlParameter();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantProductOffersSelectWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantProductOfferWidget/views/merchant-product-offers-select-widget/merchant-product-offers-select-widget.twig';
    }

    /**
     * @return void
     */
    protected function addDataUrlParameter(): void
    {
        $this->addParameter(static::PARAMETER_DATA_URL, static::ROUTE_NAME_MERCHANT_PRODUCT_OFFERS_SELECT);
    }
}
