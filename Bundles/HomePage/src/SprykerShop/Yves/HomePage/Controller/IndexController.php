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
    public const FEATURED_PRODUCT_LIMIT = 6;
    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_INCREMENTAL;

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction()
    {
        $viewData = $this->executeIndexAction();

        return $this->view(
            $viewData,
            $this->getFactory()->getHomePageWidgetPlugins(),
            '@HomePage/views/home/home.twig'
        );
    }

    /**
     * @return mixed[]
     */
    protected function executeIndexAction(): array
    {
        return [
            'featuredProductLimit' => static::FEATURED_PRODUCT_LIMIT,
        ];
    }
}
