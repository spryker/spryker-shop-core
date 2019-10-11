<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockWidgetTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        $locale = $this->getLocale();

        return [
            $this->getFactory()->createCmsBlockTwigFunction($locale),
        ];
    }
}
