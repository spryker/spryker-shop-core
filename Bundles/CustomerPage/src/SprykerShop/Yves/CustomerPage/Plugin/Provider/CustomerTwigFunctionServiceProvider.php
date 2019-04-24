<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @deprecated Use `SprykerShop\Yves\CustomerPage\Plugin\Twig\CustomerTwigPlugin` instead.
 *
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class CustomerTwigFunctionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                return $this->registerCustomerTwigFunction($twig);
            })
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function registerCustomerTwigFunction(Environment $twig)
    {
        $twig->addFunction(
            'getUsername',
            new TwigFunction('getUsername', function () {
                if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
                    return null;
                }

                return $this->getFactory()->getCustomerClient()->getCustomer()->getEmail();
            })
        );

        $twig->addFunction(
            'isLoggedIn',
            new TwigFunction('isLoggedIn', function () {
                return $this->getFactory()->getCustomerClient()->isLoggedIn();
            })
        );

        return $twig;
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
