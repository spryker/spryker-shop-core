<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\HomePage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Spryker\Shared\Storage\StorageConstants;

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

        return $this->view($data, $this->getFactory()->getHomePageWidgetPlugins());
    }

}
