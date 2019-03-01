<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetBanner\Plugin\CmsContentWidget;

use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetCmsBlockConnector\CmsContentWidgetCmsBlockConnectorFactory getFactory()
 */
class BannerContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
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
     * {@inheritdoc}
     *
     * @api
     *
     * @return callable
     */
    public function getContentWidgetFunction()
    {
        return [$this, 'contentWidgetFunction'];
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $context
     * @param string[] $banner
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Twig_Environment $twig, array $context, array $banner, $templateIdentifier = null): string
    {
        $templatePath = $this->resolveTemplatePath($templateIdentifier);

        return $twig->render($templatePath, [
            'banner' => $banner,
        ]);
    }

    /**
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath(?string $templateIdentifier = null): string
    {
        $availableTemplates = $this->widgetConfiguration->getAvailableTemplates();
        if (!$templateIdentifier || !array_key_exists($templateIdentifier, $availableTemplates)) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $availableTemplates[$templateIdentifier];
    }
}
