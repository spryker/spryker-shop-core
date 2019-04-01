<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ContentBannerWidget\ContentBannerWidgetFactory getFactory()
 */
class ContentBannerWidgetTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FUNCTION_NAME = 'cms_banner';

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
        return $this->registerCmsBannerTwigFunction($twig, $container);
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    protected function registerCmsBannerTwigFunction(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction(
            static::FUNCTION_NAME,
            new TwigFunction(static::FUNCTION_NAME, function (int $idContent, ?string $template = null) use ($twig) {
                $banner = $this->getFactory()->getContentBannerClient()->findBannerById($idContent, $this->getLocale());
                $response = [
                    'banner' => $banner,
                    'modifiers' => $template ? [$template] : [],
                ];

                return $twig->render($this->getTemplate(), $response);
            }, ['is_safe' => ['html']])
        );

        return $twig;
    }

    /**
     * @return string
     */
    protected function getTemplate(): string
    {
        return '@ContentBannerWidget/views/banner/banner.twig';
    }
}
