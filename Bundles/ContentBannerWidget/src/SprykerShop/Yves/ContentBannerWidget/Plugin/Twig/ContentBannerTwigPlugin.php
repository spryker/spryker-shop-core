<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Plugin\Twig;

use Exception;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ContentBannerWidget\ContentBannerWidgetFactory getFactory()
 */
class ContentBannerTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FUNCTION_NAME = 'content_banner';

    protected const TEMPLATE_IDENTIFIER_DEFAULT = 'default';
    protected const TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

    protected const MESSAGE_BANNER_NOT_FOUND = 'Content Product Abstract with ID %s not found.';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        return $this->registerContentBannerTwigFunction($twig, $container);
    }

    /**
     * @return array
     */
    public function getAvailableTemplates(): array
    {
        return [
            static::TEMPLATE_IDENTIFIER_DEFAULT => '@ContentBannerWidget/views/banner/banner.twig',
            static::TEMPLATE_IDENTIFIER_TOP_TITLE => '@ContentBannerWidget/views/banner/banner-top-title.twig',
        ];
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function registerContentBannerTwigFunction(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction(
            static::FUNCTION_NAME,
            new TwigFunction(static::FUNCTION_NAME, function (int $idContent, ?string $templateIdentifier = null) use ($twig) {
                try {
                    $contentBannerTypeTransfer = $this->getFactory()
                        ->getContentBannerClient()
                        ->findBannerById($idContent, $this->getLocale());

                    if (!$contentBannerTypeTransfer) {
                        return '<!-- ' . sprintf(static::MESSAGE_BANNER_NOT_FOUND, $idContent) . ' -->';
                    }
                } catch (Exception $e) {
                    return '<!-- ' . $e->getMessage() . ' -->';
                }

                return $twig->render($this->resolveTemplatePath($templateIdentifier), [
                    'banner' => $contentBannerTypeTransfer,
                ]);

            }, ['is_safe' => ['html']])
        );

        return $twig;
    }

    /**
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath(?string $templateIdentifier = null): string
    {
        if (!$templateIdentifier || !isset($this->getAvailableTemplates()[$templateIdentifier])) {
            $templateIdentifier = static::TEMPLATE_IDENTIFIER_DEFAULT;
        }

        return $this->getAvailableTemplates()[$templateIdentifier];
    }
}
