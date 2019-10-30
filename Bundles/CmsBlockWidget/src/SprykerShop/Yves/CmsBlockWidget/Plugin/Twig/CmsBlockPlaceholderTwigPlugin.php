<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget\Plugin\Twig;

use Spryker\Yves\Twig\Plugin\AbstractTwigExtensionPlugin;

/**
 * @method \SprykerShop\Yves\CmsBlockWidget\CmsBlockWidgetFactory getFactory()
 */
class CmsBlockPlaceholderTwigPlugin extends AbstractTwigExtensionPlugin
{
    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            $this->getFactory()->createCmsBlockPlaceholderTwigFunction(),
        ];
    }
}
