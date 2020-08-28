<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetChartConnector\Plugin\CmsContentWidget;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

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
     * @param \Twig\Environment $twig
     * @param mixed[] $context
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Environment $twig, array $context, $chartPluginName, $dataIdentifier = null, $templateIdentifier = null): string
    {
        $widgetContainerRegistry = $this->getFactory()->createWidgetContainerRegistry();
        $widgetContainerRegistry->add(
            $this->getFactory()->createCmsChartContentWidgetCollection()
        );

        $result = $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $chartPluginName, $dataIdentifier)
        );

        $widgetContainerRegistry->removeLastAdded();

        return $result;
    }

    /**
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier = null): string
    {
        if (!$templateIdentifier) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param mixed[] $context
     * @param string $chartPluginName
     * @param string|null $dataIdentifier
     *
     * @return mixed[]
     */
    protected function getContent(array $context, $chartPluginName, $dataIdentifier = null): array
    {
        return [
            'chartPluginName' => $chartPluginName,
            'dataIdentifier' => $dataIdentifier,
        ];
    }
}
