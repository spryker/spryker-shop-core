<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface;

class ContentBannerWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface
     */
    public function getContentBannerClient(): ContentBannerWidgetToContentBannerClientInterface
    {
        return $this->getProvidedDependency(ContentBannerWidgetDependencyProvider::CLIENT_CONTENT_BANNER);
    }
}
