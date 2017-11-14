<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Twig\Plugin\TwigFunctionPluginInterface;

class CmsBlockWidgetFactory extends AbstractFactory
{

    /**
     * @return TwigFunctionPluginInterface[]
     */
    public function getTwigFunctionPlugins()
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::TWIG_FUNCTION_PLUGINS);
    }
}
