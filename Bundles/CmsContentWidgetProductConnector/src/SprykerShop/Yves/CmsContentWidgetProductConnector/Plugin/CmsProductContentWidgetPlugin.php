<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductConnector\Plugin;

use Spryker\Yves\CmsContentWidgetProductConnector\Plugin\CmsProductContentWidgetPlugin as SprykerCmsProductContentWidgetPlugin;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Twig\Environment;

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
     * @param \Twig\Environment $twig
     * @param array $context
     * @param array|string $productAbstractSkuList $productAbstractSkuList
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Environment $twig, array $context, $productAbstractSkuList, $templateIdentifier = null)
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($this->createCmsProductContentWidgetCollection());

        $result = parent::contentWidgetFunction($twig, $context, $productAbstractSkuList, $templateIdentifier);

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function createCmsProductContentWidgetCollection(): WidgetContainerInterface
    {
        return $this->getFactory()->createCmsProductContentWidgetCollection();
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function mapProductStorageTransfer(array $productData)
    {
        return $this->getFactory()
            ->getProductStorageClient()
            ->mapProductAbstractStorageData($productData, $this->getLocale());
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array|null
     */
    protected function findProductAbstractByIdProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->getProductStorageClient()
            ->findProductAbstractStorageData($idProductAbstract, $this->getLocale());
    }
}
