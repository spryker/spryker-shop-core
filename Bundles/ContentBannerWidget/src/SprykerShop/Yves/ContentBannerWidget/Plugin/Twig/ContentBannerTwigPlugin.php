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
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function registerContentBannerTwigFunction(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction(
            static::FUNCTION_NAME,
            new TwigFunction(static::FUNCTION_NAME, function (int $idContent, ?string $template = null) use ($twig) {
                try {
                    $banner = $this->getFactory()->getContentBannerClient()->findBannerById($idContent, $this->getLocale());

                    if (!$banner) {
                        return '<!-- Content Banner with ID ' . $idContent . ' not found. -->';
                    }

                    $context = [
                        'banner' => $banner,
                        'template' => $template,
                        'modifiers' => $this->getModifiers($template),
                    ];

                    return $twig->render($this->getTemplate(), $context);
                } catch (Exception $e) {
                    return '<!-- ' . $e->getMessage() . ' -->';
                }
            }, ['is_safe' => ['html']])
        );

        return $twig;
    }

    /**
     * @param string|null $template
     *
     * @return array
     */
    protected function getModifiers(?string $template = null): array
    {
        if (!$template) {
            return [];
        }

        return ['vertical'];
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return '@ContentBannerWidget/views/banner/banner.twig';
    }
}
