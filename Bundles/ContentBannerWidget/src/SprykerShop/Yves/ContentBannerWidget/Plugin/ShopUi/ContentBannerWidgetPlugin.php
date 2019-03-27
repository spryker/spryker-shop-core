<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Plugin\ShopUi;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\ContentBannerWidget\ContentBannerWidgetConfigurationProvider\ContentBannerWidgetConfigurationProvider;
use Twig_Environment;

/**
 * @method \SprykerShop\Yves\ContentBannerWidget\ContentBannerWidgetFactory getFactory()
 */
class ContentBannerWidgetPlugin extends AbstractPlugin
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
        return [$this, 'contentBannerWidgetFunction'];
    }

    public function contentBannerWidgetFunction(
        Twig_Environment $twig,
        array $context,
        int $idContent,
        ?string $templateIdentifier = null
    ): string {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getTemplateContext($idContent)
        );
    }

    /**
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier = null): string
    {
        if (!$templateIdentifier) {
            $templateIdentifier = ContentBannerWidgetConfigurationProvider::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param int[] $idProductAbstracts
     *
     * @return array
     */
    protected function getTemplateContext(int $idContent): array
    {
        return [
            'data' => $this->getFactory()
                ->getContentBannerClient()
                ->getExecutedBannerById($idContent, $this->getLocale())
        ];
    }
}
