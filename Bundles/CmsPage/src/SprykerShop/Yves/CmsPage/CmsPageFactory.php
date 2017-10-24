<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CmsPage;

use SprykerShop\Yves\CmsPage\ResourceCreator\PageResourceCreator;
use Spryker\Yves\Kernel\AbstractFactory;

class CmsPageFactory extends AbstractFactory
{

    /**
     * @return \SprykerShop\Yves\CmsPage\ResourceCreator\PageResourceCreator
     */
    public function createPageResourceCreator()
    {
        return new PageResourceCreator();
    }

    /**
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    public function getCmsTwigRendererPlugin()
    {
        return $this->getProvidedDependency(CmsPageDependencyProvider::CMS_TWIG_CONTENT_RENDERER_PLUGIN);
    }

}
