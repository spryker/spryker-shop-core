<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetChartConnector\Plugin;

use Spryker\Yves\CmsContentWidgetChartConnector\Plugin\CmsChartContentWidgetPlugin as SprykerCmsChartContentWidgetPlugin;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\CmsContentWidgetChartConnector\CmsContentWidgetChartConnectorFactory getFactory()
 */
class CmsChartContentWidgetPlugin extends SprykerCmsChartContentWidgetPlugin
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
     * @param array|string $chartAbstractSkuList $chartAbstractSkuList
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, $chartAbstractSkuList, $templateIdentifier = null): string
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($this->createCmsChartContentWidgetCollection());

        $result = parent::contentWidgetFunction($twig, $context, $chartAbstractSkuList, $templateIdentifier);

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function createCmsChartContentWidgetCollection(): WidgetContainerInterface
    {
        return $this->getFactory()->createCmsChartContentWidgetCollection();
    }
}
