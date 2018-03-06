<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\HomePage\Controller;

use Spryker\Shared\Storage\StorageConstants;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\HomePage\HomePageFactory getFactory()
 */
class IndexController extends AbstractController
{
    const FEATURED_PRODUCT_LIMIT = 6;
    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @return array
     */
    public function indexAction()
    {
        $data = [
            'featuredProductLimit' => static::FEATURED_PRODUCT_LIMIT,
        ];

        return $this->view(
            $data,
            $this->getFactory()->getHomePageWidgetPlugins(),
            '@HomePage/views/home/home.twig'
        );
    }
}
