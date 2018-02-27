<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use Twig_SimpleFunction;

/**
 * Class CustomerTwigServiceProvider
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
            $app->extend('twig', function (\Twig_Environment $twig) {
                return $this->registerCustomerTwigFunction($twig);
            })
        );
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return \Twig_Environment
     */
    protected function registerCustomerTwigFunction(Twig_Environment $twig)
    {
        $twig->addFunction(
            'getUsername',
            new Twig_SimpleFunction('getUsername', function () {
                if (!$this->getFactory()->getCustomerClient()->isLoggedIn()) {
                    return null;
                }

                return $this->getFactory()->getCustomerClient()->getCustomer()->getEmail();
            })
        );

        $twig->addFunction(
            'isLoggedIn',
            new Twig_SimpleFunction('isLoggedIn', function () {
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
