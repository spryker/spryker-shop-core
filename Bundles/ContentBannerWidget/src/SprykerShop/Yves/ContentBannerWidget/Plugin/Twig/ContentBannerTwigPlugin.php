<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Plugin\Twig;

use Spryker\Client\ContentBanner\Exception\MissingBannerTermException;
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

    protected const MESSAGE_BANNER_NOT_FOUND = 'Content Banner with ID %s not found.';
    protected const MESSAGE_BANNER_WRONG_TYPE = '%s widget cannot display for ID %s.';
    protected const MESSAGE_BANNER_WRONG_TEMPLATE = '%s is not supported name of template .';

    protected const TEMPLATE_IDENTIFIER_DEFAULT = 'default';
    protected const TEMPLATE_IDENTIFIER_TOP_TITLE = 'top-title';

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
    protected function getAvailableTemplates(): array
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
            new TwigFunction(static::FUNCTION_NAME, function (int $idContent, string $templateIdentifier) use ($twig) {
                try {
                    $contentBannerTypeTransfer = $this->getFactory()
                        ->getContentBannerClient()
                        ->findBannerById($idContent, $this->getLocale());

                    if (!$contentBannerTypeTransfer) {
                        return '<!-- ' . sprintf(static::MESSAGE_BANNER_NOT_FOUND, $idContent) . ' -->';
                    }
                } catch (MissingBannerTermException $e) {
                    return '<!-- ' . sprintf(static::MESSAGE_BANNER_WRONG_TYPE, static::FUNCTION_NAME, $idContent) . ' -->';
                }

                if (!isset($this->getAvailableTemplates()[$templateIdentifier])) {
                    return '<!-- ' . sprintf(static::MESSAGE_BANNER_WRONG_TEMPLATE, $templateIdentifier) . ' -->';
                }

                return $twig->render(
                    $this->getAvailableTemplates()[$templateIdentifier],
                    ['banner' => $contentBannerTypeTransfer]
                );
            }, ['is_safe' => ['html']])
        );

        return $twig;
    }
}
