<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector\Plugin;

use Spryker\Yves\CmsContentWidgetProductConnector\Plugin\CmsProductContentWidgetPlugin as SprykerCmsProductContentWidgetPlugin;
use Spryker\Yves\Kernel\View\View;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorFactory getFactory()
 */
class CmsProductContentWidgetPlugin extends SprykerCmsProductContentWidgetPlugin
{

    /**
     * @return callable
     */
    public function getContentWidgetFunction()
    {
        return [$this, 'contentWidgetFunction'];
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $context
     * @param array|string $productAbstractSkuList $productAbstractSkuList
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, $productAbstractSkuList, $templateIdentifier = null)
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($this->createView());

        $result = parent::contentWidgetFunction($twig, $context, $productAbstractSkuList, $templateIdentifier);

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function createView(): View
    {
        return new View([], $this->getFactory()->getCmsProductContentWidgetPlugins());
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapProductStorageTransfer(array $productData)
    {
        return $this->getFactory()
            ->getStorageProductMapperPlugin()
            ->mapStorageProduct($productData, []);
    }

}