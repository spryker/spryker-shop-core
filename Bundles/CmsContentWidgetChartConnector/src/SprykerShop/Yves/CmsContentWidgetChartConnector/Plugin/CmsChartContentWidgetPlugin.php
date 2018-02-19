<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetChartConnector\Plugin;

use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Kernel\Widget\WidgetContainerInterface;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\CmsContentWidgetChartConnector\CmsContentWidgetChartConnectorFactory getFactory()
 */
class CmsChartContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
{
    /**
     * @var \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface
     */
    protected $widgetConfiguration;

    /**
     * @param \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface $widgetConfiguration
     */
    public function __construct(CmsContentWidgetConfigurationProviderInterface $widgetConfiguration)
    {
        $this->widgetConfiguration = $widgetConfiguration;
    }

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
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, $chartPluginName, $dataIdentifier = null, $templateIdentifier = null): string
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add($this->createCmsChartContentWidgetCollection());

        $result = $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $chartPluginName, $dataIdentifier)
        );

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier = null)
    {
        if (!$templateIdentifier) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param array $context
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return array
     */
    protected function getContent(array $context, $chartPluginName, $dataIdentifier = null)
    {
        return [
            'chartPluginName' => $chartPluginName,
            'dataIdentifier' => $dataIdentifier,
        ];
    }

    /**
     * @return \Spryker\Yves\Kernel\Widget\WidgetContainerInterface
     */
    protected function createCmsChartContentWidgetCollection(): WidgetContainerInterface
    {
        return $this->getFactory()->createCmsChartContentWidgetCollection();
    }
}
