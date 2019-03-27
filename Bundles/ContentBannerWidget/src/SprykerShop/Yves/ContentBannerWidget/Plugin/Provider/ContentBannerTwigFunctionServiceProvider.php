<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget\Plugin\Provider;

use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

class ContentBannerTwigFunctionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) use ($app) {
                return $this->registerExecutedBannerTwigFunction($twig, $app);
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param \Silex\Application $app
     *
     * @return \Twig\Environment
     */
    protected function registerExecutedBannerTwigFunction(Environment $twig, Application $app)
    {
        $twig->addFunction(
            'getExecutedBanner',
            new TwigFunction('getExecutedBanner', function (array $context, $identifie) {
                $idContentItem = $context['_view']['id'];
                return $this->getFactory()->getContentBannerClient()->getExecutedBannerById($idContentItem);
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}