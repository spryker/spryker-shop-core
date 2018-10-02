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
    public const ROUTE_PREVIEW = 'cms-preview';
    public const PARAM_PAGE = 'page';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addCmsPreviewRoute();
    }

    /**
     * @return $this
     */
    protected function addCmsPreviewRoute(): self
    {
        $this->createController(sprintf('/{cms}/preview/{%s}', static::PARAM_PAGE), self::ROUTE_PREVIEW, 'CmsPage', 'Preview', 'index')
            ->assert('cms', $this->getAllowedLocalesPattern() . 'cms|cms')
            ->value('cms', 'cms')
            ->assert(static::PARAM_PAGE, '[0-9]+');

        return $this;
    }
}
