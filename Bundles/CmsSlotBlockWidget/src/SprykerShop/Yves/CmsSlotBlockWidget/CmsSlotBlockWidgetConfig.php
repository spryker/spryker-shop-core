<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsSlotBlockWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class CmsSlotBlockWidgetConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const CMS_BLOCK_TWIG_FUNCTION_NAME = 'spyCmsBlock';

    /**
     * @api
     *
     * @return string
     */
    public function getCmsBlockTwigFunctionName(): string
    {
        return static::CMS_BLOCK_TWIG_FUNCTION_NAME;
    }
}
