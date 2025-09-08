<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantPage\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\MerchantPage\MerchantPageFactory getFactory()
 */
class MerchantSearchLinkWidget extends AbstractWidget
{
    /**
     * @uses \SprykerShop\Yves\CatalogPage\Plugin\Router\CatalogPageRouteProviderPlugin::ROUTE_NAME_SEARCH
     *
     * @var string
     */
    protected const ROUTE_SEARCH = 'search';

    /**
     * @uses \Spryker\Client\MerchantProductOfferSearch\Plugin\Search\MerchantNameSearchConfigExpanderPlugin::PARAMETER_NAME
     *
     * @var string
     */
    protected const PARAMETER_MERCHANT_NAME = 'merchant_name';

    /**
     * @var string
     */
    protected const SEARCH_BUTTON_URL = 'searchButtonUrl';

    /**
     * @param string $merchantName
     */
    public function __construct(string $merchantName)
    {
        $this->addParameter(static::SEARCH_BUTTON_URL, $this->generateSearchUrl($merchantName));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'MerchantSearchLinkWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@MerchantPage/views/merchant-search-link/merchant-search-link.twig';
    }

    /**
     * @param string $merchantName
     *
     * @return string
     */
    protected function generateSearchUrl(string $merchantName): string
    {
        return $this->getFactory()->getRouter()->generate(static::ROUTE_SEARCH, [
            static::PARAMETER_MERCHANT_NAME => [$merchantName],
        ]);
    }
}
