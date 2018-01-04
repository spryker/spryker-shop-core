<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class PreviewControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_PREVIEW = 'cms-preview';
    const PARAM_PAGE = 'page';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController(sprintf('/{cms}/preview/{%s}', static::PARAM_PAGE), self::ROUTE_PREVIEW, 'CmsPage', 'Preview', 'index')
            ->assert('cms', $allowedLocalesPattern . 'cms|cms')
            ->value('cms', 'cms')
            ->assert(static::PARAM_PAGE, '[0-9]+');
    }
}
